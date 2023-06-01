import mysql.connector
import sys
import firebase_admin
from firebase_admin import credentials, messaging

# basepath = sys.argv[1]
# jsonPath = basepath + "/app/Python/serviceAccountKey.json"
# cred = credentials.Certificate(jsonPath)

cred = credentials.Certificate("app/Python/serviceAccountKey.json")

firebase_admin.initialize_app(cred)

registration_token = "eAj_DVoyeqdWZjI2beuBX3:APA91bEMf42IWR_Bg955FIHaeecwSl9bCzOIQ83drRAeS9hiPwzFrWn1xbs9tl_UIPkVk37bmq1Yhg9ItCy--scYlnSt8jmZ5_9FzSBwOObaKXNwAWAtRfCNk4LgKV_Vak7mVMsclajV"

message = messaging.Message(
    notification=messaging.Notification(
        title="コマンドメッセージ",
        body="これはテストです。",
    ),
    # topic="todayNotificationUpcoming",
    token=registration_token,
)

response = messaging.send(message)

# conn = mysql.connector.connect(
#     host='127.0.0.1',
#     port='3306',
#     user='root',
#     password='0316arayotto',
#     database='ptrdb'
# )
# print(conn.is_connected())
print("Hello World")
