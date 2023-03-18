<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use Google_Client;
use Google_Service_YouTube;
require_once base_path().'\vendor\autoload.php';

class getdescription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getdescription';

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
        
        $data = DB::table('videos')->where('description',null)->get();
        $dataNum = count($data);

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
        
        $query = join(',',$videoIdArray);
        $items = $youtube->videos->listvideos("snippet",array('id'=>$query));
        
        $itemNum = count($items);
        for($k = 0; $k < $itemNum; $k++){
            $tempDescription = $items[$k]["snippet"]["description"];
            print_r($tempDescription);
            DB::table('videos')->where('video_id',$items[$k]["id"])
                                ->update([
                                    'description' => $tempDescription,
                                    'updated_at' => date('Y-m-d H:i:s'),
                                ]);
        }
        
        return Command::SUCCESS;
    }
}
