<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use Artisan;

class GetRss extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getrss';

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
        $channel = config('services.channel');
        $channnelNum = count($channel);
        for($i = 0; $i < $channnelNum; $i++){
            $channelId = $channel[array_keys($channel)[$i]];
            $channnelName = array_keys($channel)[$i];
            $url = "https://www.youtube.com/feeds/videos.xml?channel_id=".$channelId;
            $xml = simplexml_load_file($url);
            $json = json_encode($xml);
            $array = json_decode($json,TRUE);
            $video = $array["entry"];

            for($j = 0; $j < count($video); $j++){
                $title = $video[$j]["title"];
                $videoId = str_replace('yt:video:', '', $video[$j]["id"]);
                $channelName = $channnelName;
                $status = "rss";
                $created_at = date('Y-m-d H:i:s');
                $updated_at = date('Y-m-d H:i:s');
                $free_title = $title;

                //if include "メンバー" in title, set true to isMember
                $isMember = false;
                if(strpos($title,'メンバー') !== false){
                    $isMember = true;
                }else if(strpos($title,'member') !== false){
                    $isMember = true;
                }

                $send = [
                    'title' => $title,
                    'video_id' => $videoId,
                    'channel' => $channelName,
                    'status' => $status,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                    'free_title' => $free_title,
                    'member' => $isMember,
                    'creater_hn' => 'admin',
                ];

                //if videoId is not exist in videos table, insert data, else update title, free_title, description, and updated_at
                $isExist = DB::table('videos')->where('video_id',$videoId)->exists();
                if($isExist){
                    DB::table('videos')->where('video_id',$videoId)->update($send);
                }else{
                    DB::table('videos')->insert($send);
                }
            }
        }

        Artisan::call('command:getschedule');
        Artisan::call('command:getdescription');
        return Command::SUCCESS;
    }
}
