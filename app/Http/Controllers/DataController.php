<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Object_;
use DeepL\Translator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use Artisan;

use Google_Client;
use Google_Service_YouTube;
require_once base_path().'\vendor\autoload.php';

class DataController extends Controller
{
    public function PostSchedule(Request $request){
        $temp = $request->all();
        $send["content"] = $temp["content"];
        $send["creater_hn"] = $temp["handleName"];
        $send["creater_id"] = $temp["userId"];

        /*auto insert timestamp*/
        $send["created_at"] = date('Y-m-d H:i:s');
        $send["updated_at"] = date('Y-m-d H:i:s');

        /*insert*/
        DB::table('schedule_infos')->insert($send);

        return redirect()->route('toppage');
    }
    public function AuthorizateSchedule(Request $request){
        // insert to schedules_table
        $temp = $request->all();
        $send["start_date"] = date('Y-m-d H:i:s', strtotime($temp["start_date"]));
        $send["end_date"] = date('Y-m-d H:i:s', strtotime($temp["end_date"]));
        $send["event_title"] = $temp["event_title"];
        $send["event_en"] = $temp["event_en"];
        $send["event_code"] = $temp["event_code"];

        /*auto insert timestamp*/
        $send["created_at"] = date('Y-m-d H:i:s');
        $send["updated_at"] = date('Y-m-d H:i:s');

        /*insert and get id*/
        $scheduleId = DB::table('schedules')->insertGetId($send);

        // insert to schedules_infos_relation_table
        $relationIdArray = $temp["relation"];

        // if relationIdArray is empty, insert scheduleId and 0 to schedules_infos_relation_table
        if(count($relationIdArray) == 0){
            $sendRelation["schedule_id"] = $scheduleId;
            $sendRelation["schedule_info_id"] = 0;
            $sendRelation["created_at"] = date('Y-m-d H:i:s');
            $sendRelation["updated_at"] = date('Y-m-d H:i:s');
            DB::table('schedules_infos_relation')->insert($sendRelation);
        }else{
            foreach($relationIdArray as $relationId){
                $sendRelation["schedule_id"] = $scheduleId;
                $sendRelation["schedule_info_id"] = $relationId;
                $sendRelation["created_at"] = date('Y-m-d H:i:s');
                $sendRelation["updated_at"] = date('Y-m-d H:i:s');
                DB::table('schedules_infos_relation')->insert($sendRelation);
            }
            // update checked column in schedule_infos table where id is in relationIdArray
            DB::table('schedule_infos')->whereIn('id',$relationIdArray)->update(['checked' => true]);
        }

        return redirect()->route('admin.top');
    }

    public function PostStream(Request $request){
        $temp = $request->all();
        $send["scheduled_at"] = date('Y-m-d H:i:s', strtotime($temp["scheduled_at"]));
        $send["title"] = $temp["title"];
        $send["nostream"] = $temp["nostream"];

        $isNostream = $temp["nostream"];

        /*auto insert timestamp*/
        $send["created_at"] = date('Y-m-d H:i:s');
        $send["updated_at"] = date('Y-m-d H:i:s');

        $targetDay = new Carbon($send["scheduled_at"]);

        // if new stream data's nostream is true, delete old stream data
        if($isNostream){
            // if exsit stream data in stream table that's schedule?at day data is same new stream schedule_at's date, delete old data
            $streamData = DB::table('stream')->whereDate('scheduled_at',$targetDay)->get();
            if(count($streamData) > 0){
                DB::table('stream')->whereDate('scheduled_at',$targetDay)->delete();
            }
            /*insert*/
            DB::table('stream')->insert($send);
        }else{
            // if exsit stream data in stream table that's schedule_at day data is same new stream schedule_at's date and nostream is true, delete nostream data
            $streamData = DB::table('stream')->whereDate('scheduled_at',$targetDay)->where('nostream',1)->get();
            if(count($streamData) > 0){
                DB::table('stream')->whereDate('scheduled_at',$targetDay)->where('nostream',1)->delete();
            }
            /*insert*/
            DB::table('stream')->insert($send);
        }

        return redirect()->route('toppage');
    }
    public function PostPlayer(Request $request){
        $temp = $request->all();
        return redirect()->route('data.launched',['id' => $temp["videoId"]]);
    }
    public function PostCollabo(Request $request){
        $client = new Google_Client();
        $client->setDeveloperKey(env('GOOGLE_API_GET_INFO_KEY'));
        $youtube = new Google_Service_YouTube($client);

        $temp = $request->all();

        $items = $youtube->videos->listVideos("snippet",array('id' => $temp["videoId"]));

        $send["title"] = $items[0]["snippet"]["title"];
        $send["video_id"] = $temp["videoId"];
        $send["channel"] = $items[0]["snippet"]["channelTitle"];
        $send["status"] = "upcoming";
        $send["published_at"] = date('Y-m-d H:i:s', strtotime($items[0]["snippet"]["publishedAt"]));
        $send["creater_hn"] = $temp["handleName"];
        $send["creater_show"] = $temp["showName"];
        $send["created_at"] = date('Y-m-d H:i:s');
        $send["updated_at"] = date('Y-m-d H:i:s');
        $send["description"] = $items[0]["snippet"]["description"];
        $send["start"] = $temp["start"];
        $send["end"] = $temp["end"];
        $send["free_title"] = $items[0]["snippet"]["title"];
        DB::table('videos')->insert($send);

        $tempChannnel = $items[0]["snippet"]["channelId"];
        $channelIsNew = DB::table('collaboed')->where('channel_id',$tempChannnel)->doesntExist();
        if($channelIsNew){
            $channel["channel_id"] = $tempChannnel;
            $channel["channel_display"] = $items[0]["snippet"]["channelTitle"];
            $channel["created_at"] = date('Y-m-d H:i:s');
            $channel["updated_at"] = date('Y-m-d H:i:s');
            DB::table('collaboed')->insert($channel);
        }

        Artisan::call('command:getstatus');
        Artisan::call('command:getschedule');

        $request->session()->flash('message', '登録が完了しました。ご協力ありがとうございます！');
        return redirect('/');
    }
    public function CheckCollabo(Request $request){
        $temp = $request->all();
        $isExist = DB::table('videos')->where('video_id',$temp["videoId"])->exists();
        return response()->json(['isExist' => $isExist]);
    }
    public function CheckVideoExist(Request $request){
        $patrChannelArray = config('services.channel');
        $patrChannelName = array_keys($patrChannelArray);
        $temp = $request->all();
        $data = DB::table('videos')->where('video_id',$temp["videoId"])->first();
        if($data){
            if(in_array($data->channel,$patrChannelName)){
                return response()->json(['isExist' => true,'isCollabo'=>false,'isError'=>false]);
            }else{
                return response()->json(['isExist' => true,'isCollabo'=>true,'isError'=>false]);
            }
        }else{
            $client = new Google_Client();
            $client->setDeveloperKey(env('GOOGLE_API_GET_INFO_KEY'));
            $youtube = new Google_Service_YouTube($client);

            // if video is not exist return isCoolabo true, isExist false and isErorr true
            $item = $youtube->videos->listVideos("snippet",array('id' => $temp["videoId"]));
            if(count($item) == 0){
                return response()->json(['isExist' => false,'isCollabo'=>true,'isError'=>true]);
            }else{
                $channelDisplayName = $item[0]["snippet"]["channelTitle"];
                $channelId = $item[0]["snippet"]["channelId"];

                return response()->json(['isExist' => false,'isCollabo'=>true,'isError'=>false,'channelDisplayName'=>$channelDisplayName,'channelId'=>$channelId]);
            }
        }
    }
}
