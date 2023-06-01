<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class setFreeTitle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:setFreeTitle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = DB::table('videos')->where('free_title',null)->get();
        $dataNum = count($data);
        // copy title to free_title
        for($i = 0; $i < $dataNum; $i++){
            $tempData = json_decode(json_encode($data[$i]),true);
            $tempTitle = $tempData["title"];
            DB::table('videos')->where('video_id',$tempData["video_id"])->update(['free_title'=>$tempTitle]);
        }
        return Command::SUCCESS;
    }
}
