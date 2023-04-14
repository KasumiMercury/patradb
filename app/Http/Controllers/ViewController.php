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
        $persistentSchedule = DB::table('persistent')->where('start_date','<=',$today)->orderBy('start_date')->get();

        $monthSchedule = DB::table('schedules')->where('start_date','>',$today)->where('start_date','<',$monthDay)
                            ->orderBy('end_date')
                            ->get();
        $otherSchedule = DB::table('schedules')->Where('start_date','>',$monthDay)->orderBy('end_date')
                            ->get();

        return Inertia::render('TopPage', [
            'todayStream' => $todayStream,
            'tomorrowStream' => $tomorrowStream,
            'today' => $todaySchedule,
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
        $recentryChat = DB::table('chats')->orderBy('id','desc')->limit(10)->get();
        return Inertia::render('ChatView',[
            'recentryChat' => $recentryChat,
        ]);
    }
    public function SearchVideo(Request $request){
        $searchWord = $request->search;
        $searchOrder = $request->order;
        $orderBy = $request->by;
        $videoList = DB::table('videos')->orderBy('id');
        if($searchWord != null){
            $videoList = $videoList->whereRaw('MATCH(free_title) AGAINST(? IN BOOLEAN MODE)',[$searchWord]);
            $videoList = $videoList->orWhere('description','like','%'.$searchWord.'%');
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
            $videoList = $videoList->orderBy('id','desc');
        }
        $videoList = $videoList->get();

        return Inertia::render('VideoSearch',[
            'videoList' => $videoList,
        ]);
    }
}
