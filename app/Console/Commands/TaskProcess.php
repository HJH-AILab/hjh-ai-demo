<?php

namespace App\Console\Commands;

use App\Http\Services\Draw\Task;
use App\Models\HjhDrawTask;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class TaskProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:TaskProcess';

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
        while (true) {
            try {
                // created_at
                // where('created_at', '<', Carbon::now()->subMinutes(10))
                $tasks = HjhDrawTask::where("task_status", Task::TASK_STATUS_RUNNING)
                    ->where('created_at', '<', Carbon::now()->subMinutes(10))
                    ->where('created_at', '>', Carbon::now()->subMinutes(120))->get();
                foreach($tasks as $task) {
                    $task->task_status = Task::TASK_STATUS_LOGOUT_SYS_TIMEOUT;
                    $task->updated_at = Carbon::now();
                    $task->save();
                }
                break;
            } catch(\Exception $e) {
                Log::error("TaskProcess", array('监听任务异常: ' . $e->getMessage()));
                break;
            }
        }
        Log::info("TaskProcess", array('监听任务: task:end'));
    }
}
