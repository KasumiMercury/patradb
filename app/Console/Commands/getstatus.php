<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use Google_Client;
use Google_Service_YouTube;
require_once base_path().'\vendor\autoload.php';

use Artisan;

class getstatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getstatus';

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

        $data = DB::table('videos')->whereNot('status','archived')->whereNot('status','freechat')->get();
        $dataNum = count($data);

        if($dataNum != 0){
            $videoIdArray = [];
            if($dataNum > 30){
                $queryNum = 30;
            }else{
                $queryNum = $dataNum;
            }

            for($i = 0; $i < $queryNum; $i++){
                $tempData = json_decode(json_encode($data[$i]),true);
                array_push($videoIdArray,$tempData["video_id"]);
            }
            $query = join(",",$videoIdArray);

            $items = $youtube->videos->listVideos("snippet",array('id' => $query));

            $resultArray = [];

            for($k = 0; $k < count($items); $k++){
                $tempStatus = $items[$k]["snippet"]["liveBroadcastContent"];

                if($tempStatus == "none"){
                    $newStatus = "archived";
                }elseif($tempStatus == "upcoming"){
                    $newStatus = "upcoming";
                }elseif($tempStatus == "live"){
                    $newStatus = "live";
                }else{
                    $newStatus = "error";
                }
                $resultArray[] = [
                    "video_id" => $items[$k]["id"],
                    "newStatus" => $newStatus,
                ];
            }
            $idArray = array_column($resultArray,"video_id");

            for($m = 0; $m < count($videoIdArray); $m++){
                $isExist = array_search($videoIdArray[$m],$idArray);
                if($isExist === false){
                    DB::table('videos')->where('video_id',$videoIdArray[$m])
                                        ->update([
                                            'status' => 'archived',
                                            'updated_at' => date('Y-m-d H:i:s'),
                                        ]);
                }else{
                    DB::table('videos')->where('video_id',$videoIdArray[$m])
                                        ->update([
                                            'status' => $resultArray[$m]["newStatus"],
                                            'updated_at' => date('Y-m-d H:i:s'),
                                        ]);
                }
            }
        }

        Artisan::call('command:getArchivedScheduled');
        return Command::SUCCESS;
    }
}
