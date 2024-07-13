import RPi.GPIO as GPIO
import socketio
import pickle
import cv2
import base64
import time
from car import Car
from ultrasonic import Ultrasonic
from utils import get_ip_addr
from obstacle_avoiding import greedy_policy, get_sensor_values
from video_streamer import stream_webcam
import threading
import json
from sensor_log import saveSensorLog


def manual(data):
    global CONTROL_MODE
    if 'manual-left' in data:
        car.left()
    elif 'manual-right' in data:
        car.right()
    elif 'manual-forward' in data:
        car.forward()
    elif 'manual-stop' in data:
        car.stop()
    else:
        CONTROL_MODE = 2



def auto(data): 
    global CONTROL_MODE
    # action to avoid obstacle
    distances = ultrasonic.measure_distances()
    state = get_sensor_values(distances, data)
    oa_action = greedy_policy(Qtable_rlcar, state)
    # print(state, oa_action)
    # print(distances)

    print(CONTROL_MODE)

    if CONTROL_MODE == 2:
        if all(d < 10 for d in distances):
            car.stop()
        elif oa_action == 0: 
            car.forward()
        elif oa_action == 1:
            car.left()
        elif oa_action == 2:
            car.right()
        else:
            car.stop()	
    
    # send sensor data
    saveSensorLog(distances)
    data_send = {
        'room_name': HOST,
        'data': distances,
    }
    data_send = json.dumps(data_send)
    sio.emit('sensor',data_send)



# defind 
connected = False
running = True
conn = None
data = 'None'

car = Car()
HOST = get_ip_addr() 
PORT = 65432
rtsp_stream = stream_webcam(HOST)

HOST_SOCKET = 'http://103.149.28.31:5000'

ultrasonic = Ultrasonic(car)

# Mode for controlling the boat
CONTROL_MODE = 2 # 0: shutdown, 1: manual, 2: auto

with open('config/q_table.pkl', 'rb') as f:
    Qtable_rlcar = pickle.load(f)


def video():
    while running:
        if rtsp_stream.poll():
            print("RTSP stream stopped")
            running = False

def run():
    global running
    global rtsp_stream
    global connected
    global CONTROL_MODE
    global data
    while running:
        if rtsp_stream.poll():
            print("RTSP stream stopped")
            # running = False
            break
        if connected:
            # check for current control mode
            if 'manual' in data:
                CONTROL_MODE = 1
            elif 'auto' or 'tracking' or 'detecting' in data:
                CONTROL_MODE = 2
            elif 'shutdown' in data:
                print("Shutting down") 
                break

            # take action based on control mode
            if CONTROL_MODE == 1:
                manual(data)
            elif CONTROL_MODE == 2: 
                auto(data)
        else:
            auto('')

        # video_handle()

def on_manual(event):
    global CONTROL_MODE

    print(CONTROL_MODE)

    if CONTROL_MODE == 1:
        if event == "Left":
            manual("manual-left")
        elif event == "Right":
            manual("manual-right")
        elif event == "Up":
            manual("manual-forward")
        elif event == 's':
            manual("manual-stop")
        elif event == 'x':
            manual("manual-backward")

# def video_handle():
#     video_capture = cv2.VideoCapture(f"rtsp://{HOST}:8554/video_stream")
#     print('abcdf')
#     while True:
#         success, frame = video_capture.read()
#         if not success:
#             video_capture.set(cv2.CAP_PROP_POS_FRAMES, 0)
#             continue
#         else:
#             ret, buffer = cv2.imencode('.jpg', frame)
#             frame_b64 = base64.b64encode(buffer).decode('utf-8')
#             print(frame_b64)
#             data = {
#                 'room_name' : HOST,
#                 'data' : frame_b64,
#             }
#             # time.sleep(0.05) 
#             sio.emit('frame', data)


start = threading.Thread(target=run, name='start')

# start.start() 



sio = socketio.Client()

# Định nghĩa các event handler
@sio.event
def connect():
    print('Đã kết nối với server')
    sio.emit('create_room',HOST)
    sio.emit('join',HOST)
    start.start() 
    
@sio.event
def disconnect():
    print('Đã ngắt kết nối với server')

# lang nghe thong bao
@sio.on('msg')
def handle_message(res):
    print('Thông báo server:', res)

@sio.on('frame')
def handle_frame(res):
    print('frame:', res)

@sio.on('sensor')
def handle_sensor(res):
    # print('sensor:', res)
    a = True

@sio.on('vehicle')
def handle_vehicle(res):
    global data
    global CONTROL_MODE
    # extract data
    res  = res.split(':')
    print(res)
    # xử lý các hàm liên quan đến xe ở đây.
    if res[0] == 'shutdown':
        data = res[0]
    if res[0] == 'disconnect':
        data = 'shutdown'
    if res[0] == 'decision':
        on_manual(res[1])
    if res[0] == 'mode':
        data = res[1]
        if(data == 'manual'):
            CONTROL_MODE = 1
        else:
            CONTROL_MODE = 2



# Kết nối với server
sio.connect(HOST_SOCKET)

sio.wait()


