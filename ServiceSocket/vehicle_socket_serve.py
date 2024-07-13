import json.tool
import eventlet
import socketio
import json

# defind class
class VehicleStatus:
    
    def __init__(self, ip = '', id = '', mode='auto',multy_connect=True,members=[]):
        self.multy_connect = multy_connect #cho phép nhiều người connect
        self.mode = mode # trạng thái của xe
        self.IP = ip # chính là IP của xe
        self.ID = id # ID client của xe
        self.members = members



sio = socketio.Server(cors_allowed_origins='*')
app = socketio.WSGIApp(sio, static_files={
    '/': {'content_type': 'text/html', 'filename': 'index.html'}
})

# Khởi tạo mảng chứa room VehicleStatus

rooms = {}

# defind hàm created room tạo room
@sio.on('create_room')
def create_room(sid, data):
    # room chính = IP của xe or là ID của xe trên csdl
    room_name = data
    if room_name not in rooms:
        rooms[room_name] = VehicleStatus(data,sid)
        print(rooms[room_name])
        sio.emit('msg', f'room_created - {data}', room=sid)
        sio.emit('create_room', f'{data}')
    else:
        sio.emit('msg', f'Room already exists - {data}', room=sid)


@sio.on('join')
def join_room(sid, data):
    username = sid
    room_name = data
    if room_name in rooms:
        # check room, ktra xem nó có phải là chủ phòng hay ko
        # Nếu là chủ phòng thì sét id = sid
        # nếu là client thì join vào mảng member
        room = rooms[room_name]
        print(room)
        # if(room.ID != sid):
        if username in room.members:
            sio.emit('msg', f'You are already in the group: {data}', room=sid)
        else :
            # ktra xem trang thai ket noi la multy_connect true || false
            if room.multy_connect == True:
                room.members.append(sid)
                # tra ve status vehicle
                res = {
                    'multy_connect': room.multy_connect,
                    'mode': room.mode,
                    'IP': room.IP,
                    'ID': room.ID,
                    'members': room.members,
                }
                res = json.dumps(res)


                sio.enter_room(sid, room_name)
                sio.emit('msg', f'{sid} has joined the group - {data}', room=room_name)

                sio.emit('msg', f'done:You have joined the group - {data}', room=sid)

                # trả về trạng thái hiện tại của room khi thêm client
                sio.emit('infor', f'detail:{res}', room=room_name)

                #  emit msg về client
                sio.emit('join', f'{res}', room=sid)
               
    else:
        sio.emit('msg', f'Room does not exist - {data}', room=sid)

@sio.on('delete_room')
def delete_room(sid, data):
    room_name = data
    if room_name in rooms:
        del rooms[room_name]
        sio.emit('room_deleted', room_name)
    else:
        sio.emit('error', 'Room does not exist')


@sio.on('leave')
def leave_room(sid,data):
    username = sid
    room_name = data
    print(data)
    if room_name in rooms:
        # Nếu client out là xe thì del room
        room = rooms[room_name]
        if(room.ID != sid):
            if username in room.members:
                room.members.remove(username)
                sio.leave_room(username, room_name)  
                sio.emit('leave', f'done:You have left the group - {data}', room=sid)
        else :
            del rooms[room_name]
            sio.emit('room_deleted', room_name)


@sio.on('connect')
def connect(sid, environ):
    print(f'{sid} connected')

@sio.on('disconnect')
def disconnect(sid):
    # nếu nó có trong key rooms thì xóa phòng
    key_room = ''
    for key in rooms:
        room = rooms[key]
        if room.ID == sid:
            key_room = key
        # trong truong ho ko phai la chu phong
        if sid in room.members:
            room.members.remove(sid)
            sio.emit('msg', f'{sid} have left the group - {key}', room=key)    
    if key_room != '':
        del rooms[key_room]
        sio.emit('room_deleted', key_room)
    
    print(f'{sid} disconnected')


@sio.on('vehicle command')
def vehicle_command(sid, data):
    data = json.loads(data)
    room_name = data['room_name']
    command = data['data']
    if command :
        a = 1 

    sio.emit('vehicle', f'{command}', room=room_name)

@sio.on('msg')
def vehicle_msg(sid, data):
    data = json.loads(data)
    room_name = data['room_name']
    msg = data['data']
    sio.emit('msg', f'{msg}', room=room_name)

@sio.on('frame')
def vehicle_frame(sid, data):
    data = json.loads(data)
    room_name = data['room_name']
    txt = data['data']
    sio.emit('frame', f'{txt}', room=room_name)

@sio.on('sensor')
def vehicle_sensor(sid, data):
    data = json.loads(data)
    room_name = data['room_name']
    txt = data['data']
    sio.emit('sensor', f'{txt}', room=room_name)

@sio.on('infor')
def vehicle_infor(sid, data):
    # data = json.loads(data)
    # room_name = data['room_name']
    txt = data
    print(f'data: {txt}')
    # xử lý data
    respon = ''

    # th get all thiet bi
    if txt == 'scan_vehicles':
        res = []
        for key in rooms:
            res.append(key)
        res = json.dumps(res)
        respon = res

    sio.emit('infor', f'list:{respon}', room=sid)


# @sio.on('private')
# def private(sid, data):
#     sio.emit('vehicle', f'You send private message: {data}', room=sid)
#     print(data) 
    # print('disconnect ', sid)

if __name__ == '__main__':
    eventlet.wsgi.server(eventlet.listen(('', 5000)), app)
  
