<?php

namespace App\Jobs;

use App\Hjh\LoggerHelper;
use App\Http\Services\DrawTaskService;
use App\Http\Services\ServiceService;
use App\Models\HjhDrawTask;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessAdminJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $action;
    protected $data;

    public $timeout = 600;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($common)
    {
        $this->action = $common["action"];
        $this->data = $common["data"];
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::info("ProcessAdminJob", array(
                "action" => $this->action,
                "data" => $this->data,
            ));
            if($this->action == "query_drawtask") {
                $taskNo = $this->get($this->data, "task_no", "");
                $drawTask = HjhDrawTask::where("task_no", $taskNo)->get()->first();
                $ret = DrawTaskService::getInstance()->checkTask($drawTask);
            }
        } catch (\Exception $e) {
            Log::error("ProcessAdminJob", array(
                "message" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ));
            Log::info("ProcessAdminJob", array(
                "message" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ));
        }
    }

    public function get($arr, $key, $default = '') {
        return isset($arr[$key]) ? $arr[$key] : $default;
    }

    /**
     * @param null $exception
     * 执行失败的任务
     */
    public function failed($exception = null)
    {
    }
}
