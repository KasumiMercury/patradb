import argparse
import json
import firebase_admin
from firebase_admin import credentials, messaging

# JSON 取得
parser = argparse.ArgumentParser()
parser.add_argument('--data', help='通知のデータ(JSON形式)', required=True)
args = parser.parse_args()

data = json.loads(args.data)
topic = data['topic']
title = data['title']
message = data['message']
basePath = data['basePath']

# サービスアカウントキーの読み込み
cred = credentials.Certificate(basePath + "/app/Python/serviceAccountKey.json")

# FirebaseAdminの初期化
firebase_admin.initialize_app(cred)

# See documentation on defining a message payload.
message = messaging.Message(
    notification=messaging.Notification(
        title=title,
        body=message
    ),
    topic=topic,
)

# Send a message to the devices subscribed to the provided topic.
response = messaging.send(message)
# Response is a message ID string.
print('Successfully sent message:', response)
