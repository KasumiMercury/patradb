import mysql.connector
import firebase_admin
from firebase_admin import credentials, messaging

# サービスアカウントキーの読み込み
cred = credentials.Certificate("app\Python\serviceAccountKey.json")

# FirebaseAdminの初期化
firebase_admin.initialize_app(cred)

# FCM登録トークン
registration_token = "ctElv9XeUmfvUPhJhuQ-VS:APA91bEF9x0FkoqgmLkbPYlFVZwGqI31ZOIO7XWULZqtXMOhYuwStxhvj6-gMqaNFTpw-1z3X6CVWu9T67nwegd05K-rBDR6CWFSpCL1cu5qgWf4mrv2UKi1Z65FATSdtEsLQ0Iol9Dl"

# メッセージの作成
message = messaging.Message(
    notification=messaging.Notification(
        title="Pythonからのメッセージ",
        body="これはテストです。",
    ),
    token=registration_token,
)

# メッセージの送信
response = messaging.send(message)
print("response:", response)

conn = mysql.connector.connect(
    host='127.0.0.1',
    port='3306',
    user='root',
    password='0316arayotto',
    database='ptrdb'
)
print(conn.is_connected())
