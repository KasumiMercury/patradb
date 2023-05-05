<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Carbon\Carbon;
use Inertia\Inertia;

class ViewController extends Controller
{
    public function TopPage(){
        $today = Carbon::today()->toDateTimeString();
        $todayDay = Carbon::today();
        $tomorrow = Carbon::tomorrow()->toDateTimeString();
        $dayAfterTommorow = Carbon::tomorrow()->addDay()->toDateTimeString();
        $monthDay = Carbon::today()->addMonthNoOverflow();

        $todayStream = DB::table('stream')->where('scheduled_at','>=',$todayDay)->where('scheduled_at','<',$tomorrow)
                            ->orderBy('scheduled_at')
                            ->get();
        $tomorrowStream = DB::table('stream')->where('scheduled_at','>=',$tomorrow)->where('scheduled_at','<',$dayAfterTommorow)
                            ->orderBy('scheduled_at')
                            ->get();

        $rssStream = DB::table('videos')->where('status','upcoming')
                                        ->where('scheduled_at','>=',$today)
                                        ->orderBy('scheduled_at')->get();

        $todaySchedule = DB::table('schedules')->where('end_date','>=',$today)->where('start_date','<=',$today)
                            ->orderBy('end_date')
                            ->get();
        $persistentComing = DB::table('persistent')->where('start_date','>=',$today)->orderBy('start_date','desc')->get();
        $persistentSchedule = DB::table('persistent')->where('start_date','<=',$today)->orderBy('start_date','desc')->get();

        $monthSchedule = DB::table('schedules')->where('start_date','>',$today)->where('start_date','<',$monthDay)
                            ->orderBy('end_date')
                            ->get();
        $otherSchedule = DB::table('schedules')->Where('start_date','>',$monthDay)->orderBy('end_date')
                            ->get();

        return Inertia::render('TopPage', [
            'todayStream' => $todayStream,
            'tomorrowStream' => $tomorrowStream,
            'today' => $todaySchedule,
            'coming' => $persistentComing,
            'persistent' => $persistentSchedule,
            'rss' => $rssStream,
            'month' => $monthSchedule,
            'other' => $otherSchedule,
        ]);
    }
    public function CreateSchedule(){
        $deepl_key = config('services.deepl.key');

        return Inertia::render('CreateSchedule', [
            'deepl_key' => $deepl_key,
        ]);
    }
    public function CreatePlayer(Request $request){
        $videoId = $request->id;
        return Inertia::render('CreatePlayer', [
            'videoId' => $videoId,
        ]);
    }
    public function LaunchComplete($id){
        return Inertia::render('MemoryLaunched', [
            'videoId' => $id,
        ]);
    }
    public function ViewChat(){
        $recentryChat = DB::table('chats')->orderBy('id','desc')->limit(10)->join('videos','chats.video_id','=','videos.video_id')
        ->select('chats.*','videos.title') ->get();
        return Inertia::render('ChatView',[
            'recentryChat' => $recentryChat,
        ]);
    }
    public function SearchVideo(Request $request){
        $query = $request->query();
        $searchWord = $request->search;
        $requiredWord = $request->required;
        $excludedWord = $request->excluded;
        $searchOrder = $request->order;
        $orderBy = $request->by;
        $since = $request->since;
        $until = $request->until;

        $searchQuery = "";
        if($excludedWord != null){
            $tempExcluded = explode(' ',$excludedWord);
            foreach($tempExcluded as $word){
                $searchQuery = $searchQuery.' -'.$word;
            }
        }
        if($requiredWord != null){
            $tempWord = "";
            if($excludedWord != null){
                $tempWord = $requiredWord;
            }else if($searchWord != null){
                $tempWord = $searchWord.' '.$requiredWord;
            }else{
                $tempWord = $requiredWord;
            }
            $tempRequired = explode(' ',$tempWord);
            foreach($tempRequired as $word){
                $searchQuery = $searchQuery.' +'.$word;
            }
        }else if($excludedWord == null){
            $searchQuery = $searchWord;
        }else{
            $searchQuery = $searchQuery.' +'.$searchWord;
        }

        $videoList = DB::table('videos')->where('status','archived');
        if($until != null){
            $carbonUntil = new Carbon($until);
            $untilEnd = $carbonUntil->addDay()->toDateTimeString();
            $videoList = $videoList->where('scheduled_at','<=',$untilEnd);
        }
        if($since != null){
            $carbonSince = new Carbon($since);
            $sinceStart = $carbonSince->startOfDay()->toDateTimeString();
            $videoList = $videoList->where('scheduled_at','>=',$sinceStart);
        }
        if($searchQuery != ""){
            if($requiredWord != null){
                $tempSearch = $searchWord.' '.$requiredWord;
            }else{
                $tempSearch = $searchWord;
            }
                $videoList = $videoList->where(function ($query) use ($searchQuery,$tempSearch) {
                    $query->whereRaw('MATCH(free_title) AGAINST(? IN BOOLEAN MODE)',[$searchQuery])->orWhere('description','like','%'.$tempSearch.'%');
                });
                if($excludedWord != null){
                    $videoList = $videoList->where('description','not like','%'.$excludedWord.'%');
                }
        }
        if($searchOrder != null){
            if($searchOrder == 'asc'){
                if($orderBy == null){
                    $videoList = $videoList->orderBy('id','asc');
                }else{
                    $videoList = $videoList->orderBy($orderBy,'asc');
                }
            }else{
                if($orderBy == null){
                    $videoList = $videoList->orderBy('id','desc');
                }else{
                    $videoList = $videoList->orderBy($orderBy,'desc');
                }
            }
        }else{
            if($searchQuery == null){
                $videoList = $videoList->orderBy('scheduled_at','desc');
            }
        }
        $videoList = $videoList->paginate(10);

        return Inertia::render('VideoSearch',[
            'videoList' => $videoList,
            'query' => $query,
        ]);
    }
    public function SearchCollabo(Request $request){
        $query = $request->query();
        $temp = DB::table('videos')->where('channel','!=','patra')->where('status','archived')->orderBy('scheduled_at')->select('id','channel')->get();
        $dataList = $this->groupBy($temp,'channel');

        return Inertia::render('CollaboSearch',[
            'dataList' => $dataList,
            'query' => $query,
        ]);
    }
    private function groupBy($array, $key) {
        $result = array();
        foreach($array as $val) {
            $result[$val->$key][] = $val;
        }
        return $result;
    }
}
