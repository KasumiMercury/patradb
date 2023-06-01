<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function RegisterUser(Request $request){
        $temp = $request->all();
        $token = $temp["fcmToken"];
        // Does token exist in tokens table?
        $isExist = DB::table('fcm_tokens')->where('token', $token)->exists();
        // if not exist then insert
        if(!$isExist){
            $send["user_id"] = $temp["userId"];
            $send["token"] = $token;
            $send["created_at"] = date('Y-m-d H:i:s');
            $send["updated_at"] = date('Y-m-d H:i:s');
            DB::table('fcm_tokens')->insert($send);
        }
        return response()->json(['registered' => true]);
    }
    public function CheckUser(Request $request){
        $temp = $request->all();
        $isExist = DB::table('fcm_tokens')->where('token', $temp["fcmToken"])->exists();
        return response()->json(['isExist' => $isExist]);
    }
    public function GetSchedule(Request $request){
        $temp = $request->all();
        $result = DB::table('alert_schedules')->where('token', $temp["fcmToken"])->get();
        $data = $result->toArray();
        $schedules = array_column( $data, 'time' );
        return response()->json(['schedules' => $schedules]);
    }
    public function RegisterSchedule(Request $request){
        $temp = $request->all();
        $send["token"] = $temp["fcmToken"];
        $send["time"] = $temp["time"];
        $send["created_at"] = date('Y-m-d H:i:s');
        $send["updated_at"] = date('Y-m-d H:i:s');
        DB::table('alert_schedules')->insert($send);
        return response()->json(['registered' => true]);
    }
    public function RemoveSchedule(Request $request){
        $temp = $request->all();
        DB::table('alert_schedules')->where('token', $temp["fcmToken"])->where('time', $temp["time"])->delete();
        return response()->json(['removed' => true]);
    }
    public function ClearSchedule(Request $request){
        $temp = $request->all();
        DB::table('alert_schedules')->where('token', $temp["fcmToken"])->delete();
        return response()->json(['cleared' => true]);
    }
    public function GetTopic(Request $request){
        $temp = $request->all();
        $result = DB::table('fcm_subscribes')->where('token', $temp["fcmToken"])->get();
        $data = $result->toArray();
        $topics = array_column( $data, 'topic' );
        return response()->json(['topics' => $topics]);
    }
    public function subscribeTopic(Request $request)
    {
        $topic = $request->input('topic');
        $token = $request->input('fcmToken');

        $pythonPath = config('services.python');
        $scriptPath =  "../app/Python/";
        $command = $pythonPath." ".$scriptPath."subscribe.py ".$topic." ".$token;
        exec($command, $output);

        DB::table('fcm_subscribes')->insert(['token' => $token, 'topic' => $topic, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
        return response()->json(['subscribed' => true]);
    }
    public function unSubscribeTopic(Request $request)
    {
        $topic = $request->input('topic');
        $token = $request->input('fcmToken');

        $pythonPath = config('services.python');
        $scriptPath =  "../app/Python/";
        $command = $pythonPath." ".$scriptPath."unsubscribe.py ".$topic." ".$token;
        exec($command, $output);

        DB::table('fcm_subscribes')->where('token', $token)->where('topic', $topic)->delete();
        return response()->json(['unsubscribed' => true]);
    }
}
