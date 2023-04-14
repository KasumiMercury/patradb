<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use Google_Client;
use Google_Service_YouTube;
require_once base_path().'\vendor\autoload.php';

class getvideos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getvideos';

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

        $nextToken = null;
        $uploadsListId = "UUeLzT-7b2PBcunJplmWtoDg";

        for($m = 0; $m < 100; $m++){
            $resultArray = [];

            if($nextToken){
                $items = $youtube->playlistItems->listPlaylistItems('contentDetails,snippet',
                    array(
                        'playlistId' => $uploadsListId,
                        'pageToken' => $nextToken,
                        'maxResults' => 50
                        ));
            }else{
                $items = $youtube->playlistItems->listPlaylistItems('contentDetails,snippet',
                    array(
                        'playlistId' => $uploadsListId,
                        'maxResults' => 50
                        ));
            }

                for($k = 0; $k < count($items["items"]); $k++){
                    $tempVideoId = $items["items"][$k]["contentDetails"]["videoId"];

                    if($tempVideoId != null){

                        $isNew = DB::table('videos')->where('video_id',$tempVideoId)->doesntExist();
                        if($isNew){
                            $tempTitle = $items["items"][$k]["snippet"]["title"];
                            $tempDescription = $items["items"][$k]["snippet"]["description"];
                            $tempPublishedAt = date('Y-m-d H:i:s', strtotime($items["items"][$k]["snippet"]["publishedAt"]));

                            $resultArray[] = [
                                "title" => $tempTitle,
                                "video_id" => $tempVideoId,
                                "channel" => "patra",
                                "status" => "archived",
                                "published_at" => $tempPublishedAt,
                                "creater_hn" => "admin",
                                "creater_show" => false,
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                                "description" => $tempDescription,
                                "free_title" => $tempTitle,
                            ];
                            print_r($resultArray);
                        }

                    }
                }

                $nextToken = $items["nextPageToken"];
                if(count($resultArray) > 0){
                    DB::table('videos')->insert($resultArray);
                    echo "added：".count($resultArray);
                }
        }

        return Command::SUCCESS;
    }
}
