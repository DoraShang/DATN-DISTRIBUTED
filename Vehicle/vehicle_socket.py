import socketio

class SocketConnect():
    def __init__(self, host, port, name='socket-conn'):
        self.host = host 
        self.port = port
        self.data = 'None'
        self.connected = False
        self.running = True
        self.conn = None
        
        # Init Socket.IO client
        self.sio = socketio.Client()
        
        # Register event handlers
        self.sio.on('connect', self.on_connect)
        self.sio.on('disconnect', self.on_disconnect)
        self.sio.on('msg', self.handle_message)
        self.sio.on('frame', self.handle_frame)
        self.sio.on('sensor', self.handle_sensor)
        self.sio.on('vehicle', self.handle_vehicle)
        
    def connect(self ,host):
        self.sio.connect(host)

    # xử lý connect
    def on_connect(self):
        self.connected = True
        self.sio.emit('create_room', self.host)
        self.sio.emit('join', self.host)
    
    def on_disconnect(self):
        a = True
        self.connected = False
        self.data = 'shutdown'
    
    # xử lý massage
    def handle_message(self):
        a = True
    
    # xử lý hình ảnh
    def handle_frame(self):
        a = True

    # xử lý sensor
    def handle_sensor(self):
        a = True
    # Xử lý hành động của phương tiện
    def on_manual(self,event):
        if event == "Left":
            self.data = 'manual-left'
        elif event == "Right":
            self.data = 'manual-left'
        elif event == "Up":
            self.data = 'manual-left'
        elif event == 's':
            self.data = 'manual-left'
        elif event == 'x':
            self.data = 'manual-left'

    # Lắng nghe lệnh
    def handle_vehicle(self,data):
         # extract data
        data  = data.split(':')
        # xử lý các hàm liên quan đến xe ở đây.
        if data[0] == 'decision':
            self.on_manual(data[1])

        if data[0] == 'shutdown':
            self.data = 'shutdown'
        
        
