<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Google_Client;
use Google_Service_YouTube;
require_once base_path().'\vendor\autoload.php';

class getchat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getchat';

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
        $client->setDeveloperKey(env('GOOGLE_API_GET_CHAT_KEY'));
        $youtube = new Google_Service_YouTube($client);

        $data = DB::table('videos')->where('status','freechat')->orWhere('status','upcoming')->where('member',0)->get();
        $dataNum = count($data);

        for($i = 0;$i < $dataNum; $i++){
            $tempVideo = json_decode(json_encode($data[$i]),true);

            $queryParams = [
                'maxResults' => 2000
            ];

            $videoId = $tempVideo["video_id"];

            $response = $youtube->liveChatMessages->listLiveChatMessages($tempVideo["chat_id"],'snippet,authorDetails',$queryParams);
            $items = $response["items"];
            $itemNum = count($items);

            $newMessage = [];
            for($k = 0; $k < $itemNum; $k++){
                $tempChannelId = $items[$k]["authorDetails"]["channelId"];

                if($tempChannelId == "UCeLzT-7b2PBcunJplmWtoDg"){
                    $tempMessage = $items[$k]["snippet"]["displayMessage"];
                    $tempTime = new Carbon($items[$k]["snippet"]["publishedAt"]);
                    $tempPublishedAt = $tempTime->addHour(9)->toDateTimeString();

                    $isNew = DB::table('chats')->where('video_id',$videoId)->where('message',$tempMessage)->doesntExist();

                    if($isNew){
                        $newMessage[] = [
                            "video_id" => $videoId,
                            "message" => $tempMessage,
                            "published_at" => $tempPublishedAt,
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                        ];
                    }
                }
            }
            // print_r($newMessage);
            DB::table('chats')->insert($newMessage);
        }

        return Command::SUCCESS;
    }
}
