<?php
namespace App\Http\Services;

use App\Jobs\Hjh\Wsc as HjhWsc;
use App\Models\HjhDrawTask;
use Illuminate\Support\Facades\Log;

class DrawTaskService extends AbstractService {
    use Singleton;

    public function getDrawTaskByTaskNo($taskNo) {
        $drawtask = HjhDrawTask::where("task_no", $taskNo)->first();
        if ($drawtask) {
            Log::info("DrawTaskService getDrawTaskByTaskNo", array("drawtask" => $drawtask->toArray()));
        } else {
            Log::error("DrawTaskService getDrawTaskByTaskNo", array("message" => "Draw task not found"));
        }
        return $drawtask;
    }

    public function checkTask($drawtask)
    {
        try {
            if ($drawtask) {
                Log::info("DrawTaskCheckTask", array("drawtask" => $drawtask->toArray()));

                HjhWsc::getInstance()->checkTask($drawtask);
                
                $resultDetail = json_decode($drawtask->result_detail, true);
                if(isset($resultDetail["query_output"])) {
                    return $resultDetail["query_output"];
                }
            }
        } catch (\Exception $e) {
            Log::error("DrawTaskCheckTask", array(
                "message" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ));
        }
        if(!empty($drawtask->result_detail)) {
            $resultDetail = json_decode($drawtask->result_detail, true);
            if(isset($resultDetail["query_output"])) {
                return $resultDetail["query_output"];
            }
        }
        
        return array();
    }

    public function callback($drawtask, $data)
    {
        try {
            if ($drawtask) {
                Log::info("DrawTaskCallback", array("drawtask" => $drawtask->toArray()));

                HjhWsc::getInstance()->callback($drawtask, $data);
                
                $ret = array(
                    "status" => $drawtask->task_status
                );
            } else {
                $ret = array(
                    "status" => $drawtask->task_status
                );
                Log::error("DrawTaskCallback", array("drawtask is processed"));
            }
            return $ret;
        } catch (\Exception $e) {
            Log::error("DrawTaskCallback", array(
                "message" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ));
        }
    }

    public function callbackImg($drawtask)
    {
        try {
            if ($drawtask) {
                Log::info("DrawTaskCallback", array("drawtask" => $drawtask->toArray()));

                HjhWsc::getInstance()->callbackImg($drawtask);
                
                $ret = array(
                    "status" => $drawtask->task_status
                );
            } else {
                $ret = array(
                    "status" => $drawtask->task_status
                );
                Log::error("DrawTaskCallback", array("drawtask is processed"));
            }
            return $ret;
        } catch (\Exception $e) {
            Log::error("DrawTaskCallback", array(
                "message" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ));
        }
    }
}
