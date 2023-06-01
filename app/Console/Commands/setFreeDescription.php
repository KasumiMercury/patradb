<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class setFreeDescription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:setFreeDescription';

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
        $data = DB::table('videos')->where('free_description',null)->get();
        $dataNum = count($data);
        // copy description to free_description
        for($i = 0; $i < $dataNum; $i++){
            $tempData = json_decode(json_encode($data[$i]),true);
            $tempDescription = $tempData["description"];
            DB::table('videos')->where('video_id',$tempData["video_id"])->update(['free_description'=>$tempDescription]);
        }
        return Command::SUCCESS;
    }
}
