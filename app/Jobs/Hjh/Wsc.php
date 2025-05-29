<?php
namespace App\Jobs\Hjh;

use App\Hjh\AI\Client;
use App\Http\Services\Draw\Images;
use App\Http\Services\Draw\Task;
use App\Http\Services\HjhCloudService;
use App\Jobs\ProcessAdminJob;
use App\Models\Image;
use Exception;
use Illuminate\Support\Facades\Log;
use Image as Intervention;
use Illuminate\Support\Facades\Storage;

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
                    $this->processData($drawtask, $data, "checkTask");
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
                $this->processData($drawtask, $data, "callback");
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

    public function callbackImg($drawtask) {
        try {
            $this->processImg($drawtask);
        } catch (\Exception $e) {
            Log::error("DrawTask", array(
                "message" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ));
            Log::info("DrawTask", array(
                "message" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ));
        }
    }

    public function processData($drawtask, $data, $type = "checkTask") {
        if($data["code"] == 200) {
            if($data["data"]["task_status"] == Task::TASK_STATUS_FINISH) {
                $images = $data["data"]["images"];
                if(!empty($images)) {
                    $drawtask->images = implode(",", $images);

                    $drawtask->task_status = Task::TASK_STATUS_FINISH;
                    $drawtask->save();

                    if($type == "callback") {
                        $common = [
                            "action" => "callback_drawtask",
                            "data" => array(
                                "task_no" => $drawtask->task_no,
                            ),
                        ];
                        // 三分钟
                        ProcessAdminJob::dispatch($common)
                            ->onConnection('redis') // 指定连接
                            ->onQueue('admin');
                        Log::info("DrawTask", $common);
                    } else {
                        $this->processImg($drawtask);
                    }
                } else {
                    $drawtask->task_status = Task::TASK_STATUS_FAIL;
                }
            } else {
                $drawtask->task_status = Task::TASK_STATUS_FAIL;
            }
        } else {
            $drawtask->task_status = Task::TASK_STATUS_FAIL;
        }

        $drawtask->end_time = time();
    }

    public function processImg($drawtask) {
        if($drawtask->task_status = Task::TASK_STATUS_FINISH) {
            $model = new Image();
            $images = explode(",", $drawtask->images);
            foreach ($images as $image) {
                $model = new Image();
                Log::info("DrawTask", array(
                    "image" => $image,
                ));
                $userId = $drawtask->user_id;
                $file = file_get_contents($image);
                $imageName = uniqid(date('YmdHis')) . "hjhai";
                Storage::disk('local')->put('thumbs/' . $imageName, $file);
                $model->thumb = 'thumbs/' . $imageName;
                Log::info("DrawTask", array(
                    "thumb" => $model->thumb,
                ));

                //生成1920宽度图片
                $resource1920 = Intervention::make($file)->resize(1920, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->stream()->detach();
                Storage::disk('local')->put('thumb1920/' . $imageName, $resource1920);
                $model->thumb1920 = 'thumb1920/' . $imageName;

                //生成1280宽度图片
                $resource1280 = Intervention::make($file)->resize(1280, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->stream()->detach();
                Storage::disk('local')->put('thumb1280/' . $imageName, $resource1280);
                $model->thumb1280 = 'thumb1280/' . $imageName;

                //生成640宽度图片
                $resource640 = Intervention::make($file)->resize(640, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->stream()->detach();
                Storage::disk('local')->put('thumb640/' . $imageName, $resource640);
                $model->thumb640 = 'thumb640/' . $imageName;
                $model->desc = "好机绘AI绘图 - " . $drawtask->workflow_name;
                $model->lens = "";
                $model->size = "";
                $model->resolution = "";
                $model->aspect_ratio = "";
                $model->keywords = "hjh,image,aigc";
                $model->released = 1;
                $model->user_id = $userId;
                $model->type = Images::TYPE_AIGC; // AI生成
                $model->source = Task::SOURCE_HJH; // 来源 1-好机绘
                $model->workflow_id = $drawtask->workflow_id;
                $model->workflow_name = $drawtask->workflow_name;
                $model->save();
            }
        }
    }
}