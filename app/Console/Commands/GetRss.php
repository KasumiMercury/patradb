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
        $feed='https://www.youtube.com/feeds/videos.xml?channel_id=UCeLzT-7b2PBcunJplmWtoDg';
        $xml = simplexml_load_file($feed);
        $obj = get_object_vars($xml);
        $obj_entry = $obj["entry"];
                
        for($k = 0; $k < count($obj_entry); $k++){
            $temp = $obj_entry[$k];
            $tempArray = json_decode(json_encode($temp), true);
            $sendArray["title"] = $tempArray["title"];
            $sendArray["video_id"] = str_replace('yt:video:', '', $tempArray["id"]);
            $sendArray["Channel"] = "Patra";
            $sendArray["status"] = "rss";
            $sendArray["creater_hn"] = "admin";
            $sendArray["creater_show"] = false;
            $sendArray["created_at"] = date('Y-m-d H:i:s');
            $sendArray["updated_at"] = date('Y-m-d H:i:s');

            if (false !== strpos($tempArray["title"], 'メンバー')) {
                $sendArray["member"] = true;
            }elseif(false !== stripos($tempArray["title"], 'member')){
                $sendArray["member"] = true;
            }else{
                $sendArray["member"] = false;
            }

            foreach($sendArray as $value){
                echo $value;
                echo "\n";
               }

            $existNew = false;

            $isNew = DB::table('videos')->where('video_id',$sendArray["video_id"])->doesntExist();
            if($isNew){
                DB::table('videos')->insert($sendArray);
            }else{
                DB::table('videos')->where('video_id',$sendArray["video_id"])
                                    ->update([
                                        'title'=>$sendArray["title"],
                                        'updated_at'=>$sendArray["updated_at"]
                                    ]);
                $existNew = true;
            }
        }
        if($existNew){
            Artisan::call('command:getschedule');
        }
        return Command::SUCCESS;
    }
}
