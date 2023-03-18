<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Object_;
use DeepL\Translator;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    public function PostSchedule(Request $request){
        $temp = $request->all();
        $send["start_date"] = date('Y-m-d H:i:s', strtotime($temp["startDate"]));
        $send["end_date"] = date('Y-m-d H:i:s', strtotime($temp["endDate"]));
        $send["event_title"] = $temp["title"];
        $send["event_code"] = $temp["eventCode"];
        $send["type_id"] = $temp["type"];
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
}
