import socketio
import cv2
import json
import ipaddress
import traceback
from PIL import Image
import time
import base64
import threading
from video_proc import VideoProcessor

class BaseStation:
    def __init__(self, mode='detect'):
        self.connected = False
        self.mode = mode
    
    def connect(self, host, port=65432):
        self.vproc = VideoProcessor(host)
        self.connected = True
    
    def real_time_control(self):
        if self.connected and self.vproc.cam_cleaner.running:
            try:
                frame = self.vproc.get_latest_frame()
            except Exception:
                traceback.print_exc()
                return False, None

            return True, frame
        else:
            return False, None 

    def close(self):
        self.vproc.close()

def show_frame():
    ret, frame = bs.real_time_control()
    frame_b64 = ''
    if ret:
        # frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
        ret, frame = cv2.imencode('.jpg', frame)
        frame = frame.tobytes()
        frame_b64 = base64.b64encode(frame).decode('utf-8')
        
    else:
        img = Image.open('config/video_404.png') 
        frame_b64 = base64.b64encode(img).decode('utf-8')
    data_sent = {
        'room_name': host,
        'data': frame_b64,
    }
    data_sent = json.dumps(data_sent)
    sio.emit('frame', data_sent)
    # time.sleep(0.05)  
    # show_frame()

def thread_frame():
    while True:
        time.sleep(0.05)  
        show_frame()
     
# Init base station
bs = False
host = ''
# -----------------------------------------------
# Khởi tạo một client Socket.IO
sio = socketio.Client()

# khoi tao cac luong
frame = threading.Thread(target=thread_frame, name='Stream frame')

# Định nghĩa các event handler
@sio.event
def connect():
    print('Đã kết nối với server')

@sio.event
def disconnect():
    print('Đã ngắt kết nối với server')


@sio.on('frame')
def handle_frame(data):
    print('frame:', data)

# created
@sio.on('create_room')
def handle_create(data):
    global bs
    global host
    print('create_room:', data)
    host = data
    time.sleep(1)
    sio.emit('join',data)
    bs = BaseStation()
    bs.connect(host)
    frame.start()

# Kết nối với server
sio.connect('http://103.149.28.31:5000')


sio.wait()

