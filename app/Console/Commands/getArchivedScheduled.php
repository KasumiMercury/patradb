<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use Google_Client;
use Google_Service_YouTube;
require_once base_path().'\vendor\autoload.php';


class getArchivedScheduled extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getArchivedScheduled';

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
        $client = new Google_Client();
        $client->setDeveloperKey(env('GOOGLE_API_GET_INFO_KEY'));
        $youtube = new Google_Service_YouTube($client);

        $data = DB::table('videos')->where('status','archived')->where('scheduled_at',null)->get();
        $allNum = count($data);
        $queryCount = 1;

        if($allNum <= 50){
            $queryNum = $allNum;
        }else{
            $queryNum = 50;
        }
        $queryCount = ceil($allNum/50);

        for($k = 0; $k < $queryCount; $k++){
            $videoIdArray = [];
            for($i = 0;$i < $queryNum; $i++){
                $tempVideos = json_decode(json_encode($data[$i]),true);
                array_push($videoIdArray,$tempVideos["video_id"]);
            }
            $query = join(',',$videoIdArray);
            $items = $youtube->videos->listvideos("liveStreamingDetails,snippet",array('id'=>$query));

            $itemNum = count($items);
            for($k = 0; $k < $itemNum; $k++){
                $tempPublished = date('Y-m-d H:i:s', strtotime($items[$k]["snippet"]["publishedAt"]));
                $tempData = json_decode(json_encode($data[$k],true));

                if(isset($items[$k]["liveStreamingDetails"]["scheduledStartTime"])){
                $tempTime = date('Y-m-d H:i:s', strtotime($items[$k]["liveStreamingDetails"]["scheduledStartTime"]));
                $tempChatId = $items[$k]["liveStreamingDetails"]["activeLiveChatId"];
                DB::table('videos')->where('video_id',$tempData->video_id)
                    ->update([
                        'scheduled_at' => $tempTime,
                        'published_at' => $tempPublished,
                        'updated_at'=> date('Y-m-d H:i:s'),
                        'chat_id'=> $tempChatId
                    ]);
                }else{
                    DB::table('videos')->where('video_id',$tempData->video_id)
                    ->update([
                        'scheduled_at' => $tempPublished,
                        'published_at' => $tempPublished,
                        'updated_at'=> date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
        return Command::SUCCESS;
    }
}
