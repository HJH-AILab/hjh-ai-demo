<?php
namespace App\Jobs\Hjh;

use App\Hjh\AI\Client;
use App\Http\Services\Draw\Task;
use App\Http\Services\HjhCloudService;
use Exception;
use Illuminate\Support\Facades\Log;

class Wsc extends Base {
    use Singleton;

    public function checkTask($drawtask) {
        $userParameter = json_decode($drawtask->user_parameter, true);
        $resultDetail = json_decode($drawtask->result_detail, true);
        try {
            Log::info("DrawTask", array("wsc checkTask"));

            $data = HjhCloudService::getInstance()->query($drawtask["task_no"]);
            
            if($drawtask->task_status == Task::TASK_STATUS_FINISH) {
                if(!isset($resultDetail["query_output"])) {
                    if($data) {
                        $resultDetail["query_output"] = $data;
                        $drawtask->result_detail = json_encode($resultDetail);
                    }
                }
            } else {
                if($data) {
                    $resultDetail["query_output"] = $data;
                    $drawtask->result_detail = json_encode($resultDetail);
                    $this->processData($drawtask, $data);
                } else {
                    $drawtask->task_status = Task::TASK_STATUS_FAIL;
                }
            }
            
            $drawtask->save();

            return $drawtask;
        } catch (\Exception $e) {
            Log::error("DrawTask", array(
                "message" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ));
            Log::info("DrawTask", array(
                "message" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ));
            
            if($drawtask->task_status == Task::TASK_STATUS_FINISH) {

            } else {
                $drawtask->task_status = Task::TASK_STATUS_FAIL;
                $result = array(
                    "code" => $e->getCode(),
                    "msg" => "Exception:" . $e->getMessage(),
                );
                $resultDetail["query"] = $result;
                $drawtask->result_detail = json_encode($resultDetail);
                $drawtask->save();
            }
            
            throw $e;
        }
    }

    public function callback($drawtask, $data) {
        try {
            if($drawtask->task_status == Task::TASK_STATUS_FINISH) {

            } else {
                $this->processData($drawtask, $data);
            }
            
            $resultDetail = json_decode($drawtask->result_detail, true);
            $resultDetail["callback"] = $data;
            $drawtask->result_detail = json_encode($resultDetail);

            $drawtask->save();
        } catch (\Exception $e) {
            Log::error("DrawTask", array(
                "message" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ));
            Log::info("DrawTask", array(
                "message" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ));
            $result = array(
                "code" => $e->getCode(),
                "msg" => "Exception:" . $e->getMessage(),
            );
            $resultDetail["callback"] = $result;
            $drawtask->result_detail = json_encode($resultDetail);
            if($drawtask->task_status == Task::TASK_STATUS_FINISH) {

            } else {
                $drawtask->task_status = Task::TASK_STATUS_FAIL;
                $drawtask->end_time = getMillisecond();
            }
            $drawtask->save();
        }
    }

    public function processData($drawtask, $data) {
        if($data["code"] == 200) {
            if($data["data"]["task_status"] == Task::TASK_STATUS_FINISH) {
                $images = $data["data"]["images"];
                if(!empty($images)) {
                    $drawtask->images = implode(",", $images);

                    $drawtask->task_status = Task::TASK_STATUS_FINISH;
                    $drawtask->save();
                } else {
                    $drawtask->task_status = Task::TASK_STATUS_FAIL;
                }
            } else {
                $drawtask->task_status = Task::TASK_STATUS_FAIL;
            }
        } else {
            $drawtask->task_status = Task::TASK_STATUS_FAIL;
        }

        $drawtask->end_time = getMillisecond();
    }
}