import argparse
import json
import mysql.connector
import firebase_admin
from firebase_admin import credentials, messaging

# JSON 取得
parser = argparse.ArgumentParser()
parser.add_argument('--data', help='通知のデータ(JSON形式)', required=True)
args = parser.parse_args()

data = json.loads(args.data)
time = data['time']
title = data['title']
message = data['message']
basePath = data['basePath']

# サービスアカウントキーの読み込み
cred = credentials.Certificate(basePath + "/app/Python/serviceAccountKey.json")

# FirebaseAdminの初期化
firebase_admin.initialize_app(cred)

conn = mysql.connector.connect(
    host='127.0.0.1',
    port='3306',
    user='root',
    password='0316arayotto',
    database='ptrdb'
)
tokens = []

# See documentation on defining a message payload.
message = messaging.Message(
    notification=messaging.Notification(
        title=title,
        body=message
    ),
    tokens=tokens,
)

# Send a message to the devices subscribed to the provided topic.
response = messaging.send(message)
# Response is a message ID string.
print('Successfully sent message:', response)
