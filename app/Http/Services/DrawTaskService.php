<?php
namespace App\Http\Services;

use App\Hjh\AI\Client;
use App\Hjh\Exceptions\NotFoundException;
use App\Hjh\LoggerHelper;
use App\Hjh\Output;
use App\Http\Services\Draw\Task;
use App\Http\Services\Draw\Service;
use App\Http\Services\Draw\DrawTask;
use App\Http\Services\Draw\Workflow;
use App\Http\Services\System\Config;
use App\Models\DrawDevice;
use App\Models\DrawQueue;
use App\Models\DrawService;
use App\Models\DrawWorkflow;
use App\Models\DrawWork;
use App\Models\SystemConfig;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;
use Modules\BE\Models\DrawDrawtask;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Iai\V20200303\IaiClient;
use TencentCloud\Iai\V20200303\Models\DetectFaceAttributesRequest;
use App\Http\Services\DrawTask\Workflow\Faceswap;
use App\Http\Services\DrawTask\Workflow\Preprocess;
use App\Http\Services\DrawTask\Workflow\Scale;
use App\Http\Services\DrawTask\Workflow\Upscale;
use App\Jobs\Hjh\Wsc as HjhWsc;
use App\Jobs\ProcessAdminJob;
use App\Jobs\ProcessAIDrawTask;
use Illuminate\Support\Facades\Log;
use Modules\OpenAPI\Services\BpTaskService;

class DrawTaskService extends AbstractService {
    use Singleton;

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
                Log::error("DrawTaskCallback", "drawtask is processed");
            }
            return $ret;
        } catch (\Exception $e) {
            Log::error("DrawTaskCallback", $e);
        }
    }
}
