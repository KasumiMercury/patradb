<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Object_;
use DeepL\Translator;
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
        $send["start_date"] = date('Y-m-d H:i:s', strtotime($temp["startDate"]));
        $send["end_date"] = date('Y-m-d H:i:s', strtotime($temp["endDate"]));
        $send["event_title"] = $temp["title"];
        $send["event_code"] = $temp["eventCode"];
        $send["creater_hn"] = $temp["handleName"];
        $send["creater_show"] = $temp["showName"];

        /*translate method*/
        $authKey = config('services.deepl.key');
        $translator = new Translator($authKey);
        $result = $translator->translateText($temp["title"], null, 'en-US');

        $send["event_en"] = $result->text;

        /*auto insert timestamp*/
        $send["created_at"] = date('Y-m-d H:i:s');
        $send["updated_at"] = date('Y-m-d H:i:s');

        /*insert*/
        DB::table('schedules')->insert($send);

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

        $request->session()->flash('message', '登録が完了しました。ご協力ありがとうございます！');
        return redirect('/');
    }
    public function CheckCollabo(Request $request){
        $temp = $request->all();
        $isExist = DB::table('videos')->where('video_id',$temp["videoId"])->exists();
        return response()->json(['isExist' => $isExist]);
    }
}
