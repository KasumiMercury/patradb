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
        $tomorrow = Carbon::tomorrow()->toDateTimeString();
        $monthDay = Carbon::today()->addMonthNoOverflow();

        $todayStream = DB::table('schedules')->where('start_date','>',$today)->where('end_date','<=',$tomorrow)
                            ->where('type_id',"stream")
                            ->orderBy('start_date')
                            ->get();

        $todaySchedule = DB::table('schedules')->where('end_date','>=',$today)->where('start_date','<=',$today)
                            ->where('type_id',null)
                            ->orderBy('end_date')
                            ->get();
        $monthSchedule = DB::table('schedules')->where('start_date','>',$today)->where('start_date','<',$monthDay)
                            ->where('type_id',null)
                            ->orderBy('end_date')
                            ->get();
        $otherSchedule = DB::table('schedules')->Where('start_date','>',$monthDay)->orderBy('end_date')
                            ->where('type_id',null)
                            ->get();
                            
        return Inertia::render('TopPage', [
            'stream' => $todayStream,
            'today' => $todaySchedule,
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
}
