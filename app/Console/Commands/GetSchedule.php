<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use Google_Client;
use Google_Service_YouTube;
require_once base_path().'\vendor\autoload.php';

class GetSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getschedule';

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
        
        $data = DB::table('videos')->where('status','rss')->get();
        $dataNum = count($data);
        $videoIdArray = [];
        for($i = 0;$i < $dataNum; $i++){
            $tempVideos = json_decode(json_encode($data[$i]),true);
            array_push($videoIdArray,$tempVideos["video_id"]);
        }
        $query = join(',',$videoIdArray);
        $items = $youtube->videos->listvideos("liveStreamingDetails,status",array('id'=>$query));

        $itemNum = count($items);
        for($k = 0; $k < $itemNum; $k++){
            $tempTime = date('Y-m-d H:i:s', strtotime($items[$k]["liveStreamingDetails"]["scheduledStartTime"]));
            $tempChatId = $items[$k]["liveStreamingDetails"]["activeLiveChatId"];
            $tempData = json_decode(json_encode($data[$k],true));
            DB::table('videos')->where('video_id',$tempData->video_id)
                ->update([
                    'status' => 'upcoming',
                    'scheduled_at' => $tempTime,
                    'updated_at'=> date('Y-m-d H:i:s'),
                    'chat_id'=> $tempChatId
                ]);
        }

        return Command::SUCCESS;
    }
}
