<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Google_Client;
use Google_Service_YouTube;
require_once base_path().'\vendor\autoload.php';

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

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

        $pythonPath = config('services.python');
        $basePath = base_path();
        $scriptPath = app_path('Python/TopicNotification.py');
        $messageData = [];

        $data = DB::table('videos')
                    ->where('status', '=', 'rss')
                    ->get();
        $dataNum = count($data);

        $videoIdArray = [];
        for($i = 0;$i < $dataNum; $i++){
            $tempVideos = json_decode(json_encode($data[$i]),true);
            array_push($videoIdArray,$tempVideos["video_id"]);
        }
        $query = join(',',$videoIdArray);
        $items = $youtube->videos->listvideos("liveStreamingDetails",array('id'=>$query));

        $itemNum = count($items);
        for($k = 0; $k < $itemNum; $k++){
            $tempData = json_decode(json_encode($data[$k],true));
            $tempTitle = $tempData->title;
            $tempVideoId = $tempData->video_id;
            // if liveStreamingDetails is null, set status to "archived", else set status to "upcoming"
            if($items[$k]["liveStreamingDetails"] == null){
                DB::table('videos')->where('video_id',$tempData->video_id)
                ->update([
                    'status' => 'archived',
                    'updated_at'=> date('Y-m-d H:i:s'),
                ]);
            }else{
                $tempTime = date('Y-m-d H:i:s', strtotime($items[$k]["liveStreamingDetails"]["scheduledStartTime"]));
                $tempChatId = $items[$k]["liveStreamingDetails"]["activeLiveChatId"];
                DB::table('videos')->where('video_id',$tempData->video_id)
                ->update([
                    'status' => 'upcoming',
                    'scheduled_at' => $tempTime,
                    'chat_id' => $tempChatId,
                    'updated_at'=> date('Y-m-d H:i:s'),
                ]);
                $isStreamSchedule = DB::table('stream')->where('scheduled_at',$tempTime)->exists();
            }

            if($isStreamSchedule){
                DB::table('stream')->where('scheduled_at',$tempTime)->update([
                    'video_id' => $tempVideoId,
                    'updated_at'=> date('Y-m-d H:i:s'),
                ]);
                $targetDay = new Carbon($tempTime);

                if($targetDay->isToday()){
                    // notification today schedule stream
                    $messageData = [
                        'topic' => 'todayNotificationUpcoming',
                        'title' => '配信枠を検知しました。',
                        'message' => '配信枠が開始されました。',
                        'basePath' => $basePath,
                    ];
                }else{
                    // notification all schedule stream
                    $messageData = [
                        'topic' => 'allNotificationUpcoming',
                        'title' => '配信枠を検知しました。',
                        'message' => '配信枠が開始されました。',
                        'basePath' => $basePath,
                    ];
                }
            }else{
                DB::table('stream')->insert([
                    'title' => $tempTitle,
                    'video_id' => $tempVideoId,
                    'scheduled_at' => $tempTime,
                    'nostream' => 0,
                    'created_at'=> date('Y-m-d H:i:s'),
                    'updated_at'=> date('Y-m-d H:i:s'),
                ]);
                $targetDay = new Carbon($tempTime);

                if($targetDay->isToday()){
                    // notification today different time schedule stream
                    $messageData = [
                        'topic' => 'todayCautionDifferentTime',
                        'title' => '予定と異なる配信枠を検知しました。',
                        'message' => '予定と異なる配信枠が開始されました。',
                        'basePath' => $basePath,
                    ];
                }else{
                    // notification all different time schedule stream
                    $messageData = [
                        'topic' => 'allCautionDifferentTime',
                        'title' => '予定と異なる配信枠を検知しました。',
                        'message' => '予定と異なる配信枠が開始されました。',
                        'basePath' => $basePath,
                    ];
                }
            }
            $json = json_encode($messageData);
            $process = new Process([$pythonPath, $scriptPath, "--data=$json"]);

            try {
                $process->mustRun();
                $response = $process->getOutput();

            } catch (ProcessFailedException $exception) {
                \Log::error($exception->getMessage());
            }
        }

        return Command::SUCCESS;
    }
}
