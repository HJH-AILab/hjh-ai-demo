<?php

namespace App\Console\Commands;

use App\Http\Services\Draw\Task;
use App\Jobs\ProcessAdminJob;
use App\Models\HjhDrawTask;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class TestTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:TestTask';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '任务';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info("TaskProcess", array('监听任务: task:start'));
       try {
            $createTaskNo = "1202505281502238471504";
            $common = [
                "action" => "query_drawtask",
                "data" => array(
                    "task_no" => $createTaskNo,
                ),
            ];
            // 三分钟
            ProcessAdminJob::dispatch($common)
                ->onQueue('admin');
        } catch(\Exception $e) {
            Log::error("TaskProcess", array('监听任务异常: ' . $e->getMessage()));
        }
        Log::info("TaskProcess", array('监听任务: task:end'));
    }
}
