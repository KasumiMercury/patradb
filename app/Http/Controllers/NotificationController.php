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
}
