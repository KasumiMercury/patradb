<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class getinfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getinfo';

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
        $youtube_id = 'UCeLzT-7b2PBcunJplmWtoDg';
        //APIキー
        $api_key = env('GOOGLE_API_GET_INFO_KEY');
        
        //YouTube Data APIのURL
        $youtube_api_url = 'https://www.googleapis.com/youtube/v3/';
        $youtube_api_resource = 'channels';
        $youtube_api_para = 'snippet,statistics';
        $youtube_api_q = '?part='.$youtube_api_para.'&id='.$youtube_id.'&key='.$api_key;
        
        $youtube_api_url .= $youtube_api_resource.$youtube_api_q;
        function ag2curl_youtube($url){
            $option = [
              CURLOPT_RETURNTRANSFER => true,//データを文字列に変換して返す
              CURLOPT_TIMEOUT        => 30//タイムアウト秒数
            ];
            $ch = curl_init($url);//cURLセッションを初期化
            curl_setopt_array($ch, $option);//転送用の複数のオプションを設定
            $json = curl_exec($ch);//cURLセッションを実行
            if(curl_errno($ch)){ //エラー番号を返す(エラーが発生しない場合、0)
              global $youtube_error;
              $youtube_error = '<p>cURL error '.curl_errno($ch).' : '.curl_error($ch).'</p>';
              $json = false;
            }
            curl_close($ch);//cURLセッションを閉じて処理を終了
            return $json;
          }
          
          //APIからJSONデータを取得
          $json = ag2curl_youtube($youtube_api_url);
          
          if($json){
            //JSON文字列をデコードして連想配列形式のオブジェクトを格納
            $obj = json_decode($json, true);
          
            //情報を取得
            @$yt_items = $obj['items'][0];
            @$yt_ch_viewCount = $yt_items['statistics']['viewCount'];
            @$yt_ch_subscriberCount = $yt_items['statistics']['subscriberCount'];

            $isNew = DB::table('infos')->where('subscriber',$yt_ch_subscriberCount)->doesntExist();
            if($isNew){
                DB::table('infos')->insert([
                    "subscriber"=>$yt_ch_subscriberCount,
                    "view"=>$yt_ch_viewCount,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s')
                ]);
            }else{
                DB::table('infos')->where('subscriber',$yt_ch_subscriberCount)->update([
                    "view"=>$yt_ch_viewCount,
                    'updated_at'=>date('Y-m-d H:i:s')
                ]);
            }
            print_r($yt_ch_viewCount);
            print_r($yt_ch_subscriberCount);
          }else{
            echo $youtube_error;
          }
        return Command::SUCCESS;
    }
}
