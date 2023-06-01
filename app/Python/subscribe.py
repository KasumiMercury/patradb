import sys
import firebase_admin
from firebase_admin import credentials, messaging

# Firebase Admin SDKの初期化
cred = credentials.Certificate("../app/Python/serviceAccountKey.json")
# cred = credentials.Certificate("app/Python/serviceAccountKey.json")
firebase_admin.initialize_app(cred)

# トピックとトークンの取得
topic = sys.argv[1]
token = sys.argv[2]

# topic = "todayCautionChangeTime"
# token = "eAj_DVoyeqdWZjI2beuBX3:APA91bEMf42IWR_Bg955FIHaeecwSl9bCzOIQ83drRAeS9hiPwzFrWn1xbs9tl_UIPkVk37bmq1Yhg9ItCy--scYlnSt8jmZ5_9FzSBwOObaKXNwAWAtRfCNk4LgKV_Vak7mVMsclajV"

# トピックにトークンをサブスクライブする
response = messaging.subscribe_to_topic(token, topic)

print(response.success_count, 'tokens were subscribed successfully')
