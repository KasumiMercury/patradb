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
        $now = Carbon::now()->toDateTimeString();
        $today = Carbon::today()->toDateTimeString();
        $tomorrow = Carbon::tomorrow()->toDateTimeString();
        $dayAfterTommorow = Carbon::tomorrow()->addDay()->toDateTimeString();
        $monthDay = Carbon::today()->addMonthNoOverflow();

        $todayStream = DB::table('stream')
                            ->where('stream.scheduled_at','>=',$today)->where('stream.scheduled_at','<=',$tomorrow)
                            ->orderBy('stream.scheduled_at')
                            ->leftJoin('videos','stream.video_id','=','videos.video_id')
                            ->select('stream.*','videos.status')
                            ->get();
        $tomorrowStream = DB::table('stream')->where('scheduled_at','>',$tomorrow)->where('scheduled_at','<',$dayAfterTommorow)
                            ->orderBy('scheduled_at')
                            ->get();

        $rssStream = DB::table('videos')->where('status','upcoming')
                                        ->orderBy('scheduled_at')->get();

        $todaySchedule = DB::table('schedules')->where('end_date','>=',$today)->where('start_date','<=',$today)
                            ->orderBy('end_date')
                            ->get();
        $persistentComing = DB::table('persistent')->where('start_date','>=',$today)->orderBy('start_date','desc')->get();
        $persistentSchedule = DB::table('persistent')->where('start_date','<=',$today)->orderBy('start_date','desc')->get();

        $monthSchedule = DB::table('schedules')->where('end_date','>=',$today)->where('start_date','<',$monthDay)
                            ->orderBy('end_date')
                            ->get();

        return Inertia::render('TopPage', [
            'todayStream' => $todayStream,
            'tomorrowStream' => $tomorrowStream,
            'today' => $todaySchedule,
            'coming' => $persistentComing,
            'persistent' => $persistentSchedule,
            'rss' => $rssStream,
            'month' => $monthSchedule,
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
        // set searchQuery by query
        // if exist requiredWord, add + to searchQuery
        // if exist excludedWord, add - to searchQuery
        if($searchWord != null){
            $searchQuery = $searchWord;
        }
        if($requiredWord != null){
            $searchQuery = $searchQuery.' +'.$requiredWord;
        }
        if($excludedWord != null){
            $searchQuery = $searchQuery.' -'.$excludedWord;
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
            // if($requiredWord != null){
            //     $tempSearch = $searchWord.' '.$requiredWord;
            // }else{
            //     $tempSearch = $searchWord;
            // }
                // $videoList = $videoList->where(function ($query) use ($searchQuery,$tempSearch) {
                //     $query->whereRaw('MATCH(free_title) AGAINST(? IN BOOLEAN MODE)',[$searchQuery])->orWhere('description','like','%'.$tempSearch.'%');
                // });
                // if($excludedWord != null){
                //     $videoList = $videoList->where('description','not like','%'.$excludedWord.'%');
                // }
                $videoList = $videoList
                                ->where(function ($query) use ($searchQuery) {
                                    $query->whereRaw("MATCH (free_title) AGAINST (? IN BOOLEAN MODE)", [$searchQuery])
                                        ->orWhereRaw("MATCH (free_description) AGAINST (? IN BOOLEAN MODE)", [$searchQuery]);
                                });
        }
        // if($searchOrder != null){
        //     if($searchOrder == 'asc'){
        //         if($orderBy != null){
        //             $videoList = $videoList->orderBy($orderBy,'asc');
        //         }else{
        //             if($searchQuery == null){
        //                 $videoList = $videoList->orderBy('scheduled_at','asc');
        //             }
        //         }
        //     }else{
        //         if($orderBy != null){
        //             $videoList = $videoList->orderBy($orderBy,'desc');
        //         }else{
        //             if($searchQuery == null){
        //                 $videoList = $videoList->orderBy('scheduled_at','desc');
        //             }
        //         }
        //     }
        // }else{
        //     if($searchQuery == null){
        //         $videoList = $videoList->orderBy('scheduled_at','desc');
        //     }else{
        //     }
        // }
        if($orderBy != null){
            if($searchOrder != null){
                if($searchOrder == 'asc'){
                    $videoList = $videoList->orderBy($orderBy,'asc');
                }else{
                    $videoList = $videoList->orderBy($orderBy,'desc');
                }
            }else{
                $videoList = $videoList->orderBy($orderBy,'desc');
            }
        }else{
            if($searchOrder != null){
                if($searchOrder == 'asc'){
                    $videoList = $videoList->orderByRaw("(CASE WHEN MATCH (free_title) AGAINST (?) THEN 1 ELSE 0 END),
                                                        (CASE WHEN MATCH (free_description) AGAINST (?) THEN 1 ELSE 0 END)",
                                                        [$searchQuery, $searchQuery]);
                }else{
                    $videoList = $videoList->orderByRaw("(CASE WHEN MATCH (free_title) AGAINST (?) THEN 1 ELSE 0 END) DESC,
                                                        (CASE WHEN MATCH (free_description) AGAINST (?) THEN 1 ELSE 0 END) DESC",
                                                        [$searchQuery, $searchQuery]);
                }
            }else{
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
