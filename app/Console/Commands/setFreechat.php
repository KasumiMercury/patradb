<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class setFreechat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:setFreechat';

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
        DB::table('videos')->whereIn('video_id',['xWoihJqM3rs','bwf8wc0yZ0U'])->update(['title' => 'freechat','status'=>'freechat']);
        return Command::SUCCESS;
    }
}
