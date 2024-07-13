import json
import os
import random
file_path = 'data_sensor.json'

def saveSensorLog(new_data):

    # Read existing data from the JSON file if it exists
    if os.path.exists(file_path):
        with open(file_path, 'r') as file:
            try:
                data = json.load(file)
            except json.JSONDecodeError:
                data = []
    else:
        data = []

    # Append the new data to the list
    data.append(new_data)

    # Write the updated list back to the JSON file
    with open(file_path, 'w') as file:
        json.dump(data, file, indent=4)


def clearSensorLog():
    # Clear the existing value of JSON file
    if os.path.exists(file_path):
        os.remove(file_path)


# clearSensorLog()

# count = 0
# while True:
#     count+=1
#     random_array = [random.randint(0, 100) for _ in range(5)]
#     saveSensorLog(random_array)
#     if count == 50:
#         break