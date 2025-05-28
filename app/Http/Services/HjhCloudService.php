<?php
namespace App\Http\Services;

use App\Jobs\ProcessAdminJob;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use InvalidArgumentException;

class HjhCloudService extends AbstractService {
    use Singleton;

    public function create($userId, $image, $imageUrl, $format, $workflowId, $workflowName, $createTaskNo = "") {
        try {
            $params = array(
                "image_url" => $imageUrl,
                "format" => $format,
                "workflow_id" => $workflowId,
                "workflow_name" => $workflowName,
                "create_task_no" => $createTaskNo,
                "callback_url" => env("APP_URL") . "/api/hjh-callback",
            );
            HjhTaskService::getInstance()->create($userId, $params);
            $base64 = HjhImageService::getImageBase64($image);
            $params["image"] = $base64;
            Log::info("hjhcloud", $params);
            $token = $this->getToken();
            $res = \App\Hjh\AI\Client::getCallbackHttp()->withHeaders([
                'Authorization' => "Bearer $token",
                ])
                ->post(env("HJHCLOUD_URL") . "/api/openapi/task/create", $params)->json();
            Log::info("hjhcloud", array(
                "params" => $params,
                "res" => $res,
            ));
            if($res && $res["code"] == 200) {
                $common = [
                    "action" => "query_drawtask",
                    "data" => array(
                        "task_no" => $createTaskNo,
                    ),
                ];
                // 三分钟
                ProcessAdminJob::dispatch($common)
                    ->onQueue('admin')
                    ->delay(180);
                $list = $res["data"];
                return $list;
            } else {
                throw new InvalidArgumentException($res["message"]);
            }
        } catch(Exception $e) {
            Log::error("hjhcloud", array($e->getMessage(), $e->getTraceAsString()));
            Log::info("create fail", array($e->getMessage(), $e->getTraceAsString()));
            return array();
        }
    }

    /**
     * "data": {
		"task_no": "1_1202505281502238471504",
		"task_status": 3,
		"images": [
			"https://ai.wepromo.cn/images/openapi_img/tmp/2025/05/28/1_0_70_00001_faceswap_.webp",
			"https://ai.wepromo.cn/images/openapi_img/tmp/2025/05/28/1_0_70_00002_faceswap_.webp",
			"https://ai.wepromo.cn/images/openapi_img/tmp/2025/05/28/1_0_70_00003_faceswap_.webp",
			"https://ai.wepromo.cn/images/openapi_img/tmp/2025/05/28/1_0_70_00004_faceswap_.webp"
		],
		"source": [
			"https://ai.wepromo.cn/images/openapi_img/tmp/2025/05/28/1_0_70_00001_.webp",
			"https://ai.wepromo.cn/images/openapi_img/tmp/2025/05/28/1_0_70_00002_.webp",
			"https://ai.wepromo.cn/images/openapi_img/tmp/2025/05/28/1_0_70_00003_.webp",
			"https://ai.wepromo.cn/images/openapi_img/tmp/2025/05/28/1_0_70_00004_.webp"
		],
		"faceswap": [
			"https://ai.wepromo.cn/images/openapi_img/tmp/2025/05/28/1_0_70_00001_faceswap_.webp",
			"https://ai.wepromo.cn/images/openapi_img/tmp/2025/05/28/1_0_70_00002_faceswap_.webp",
			"https://ai.wepromo.cn/images/openapi_img/tmp/2025/05/28/1_0_70_00003_faceswap_.webp",
			"https://ai.wepromo.cn/images/openapi_img/tmp/2025/05/28/1_0_70_00004_faceswap_.webp"
		]
	}
     */
    public function query($taskNo, $isReturnImage = 1) {
        try {
            $params = array(
                "task_no" => $taskNo,
                "is_return_image" => $isReturnImage,
            );
            $token = $this->getToken();
            $res = \App\Hjh\AI\Client::getCallbackHttp()->withHeaders([
                'Authorization' => "Bearer $token",
                ])
                ->get(env("HJHCLOUD_URL") . "/api/openapi/task/query", $params)->json();
            Log::info("hjhcloud", array(
                "token" => $token,
                "res" => $res,
            ));
            if($res) {
                return $res;
            } else {
                throw new InvalidArgumentException("no res");
            }
        } catch(Exception $e) {
            Log::error("hjhcloud", array($e->getMessage(), $e->getTraceAsString()));
            Log::info("query fail", array($e->getMessage(), $e->getTraceAsString()));
            return array();
        }
    }

    public function getWorkflows() {
        try {
            $params = array(
                
            );
            $token = $this->getToken();
            $res = \App\Hjh\AI\Client::getCallbackHttp()->withHeaders([
                'Authorization' => "Bearer $token",
                ])
                ->get(env("HJHCLOUD_URL") . "/api/openapi/workflow/getWorkflows", $params)->json();
            Log::info("hjhcloud", array(
                "token" => $token,
                "res" => $res,
            ));
            if($res && $res["code"] == 200) {
                $list = $res["data"];
                return $list;
            } else {
                throw new InvalidArgumentException($res["message"]);
            }
        } catch(Exception $e) {
            Log::error("hjhcloud", array($e->getMessage(), $e->getTraceAsString()));
            Log::info("getWorkflows fail", array($e->getMessage(), $e->getTraceAsString()));
            return array();
        }
    }

    public function getToken() {
        try {
            $redis = $this->fetchRedisConnect();
            $key = "hjhcloud:openapi:token";
            $token = $redis->get($key);
            if(empty($token)) {
                $url = env("HJHCLOUD_URL") . "/api/openapi/login/getToken";
                $params = array(
                    "client_id" => env("HJHCLOUD_CLIENT_ID"),
                    "client_secret" => env("HJHCLOUD_CLIENT_SECRET"),
                );
                Log::info("hjhcloud", array(
                    "url" => $url,
                    "params" => $params,
                ));
                $res = \App\Hjh\AI\Client::getCallbackHttp()
                    ->post($url, $params)->json();
                Log::info("hjhcloud", array(
                    "url" => $url,
                    "params" => $params,
                    "res" => $res,
                ));
                if($res && $res["code"] == 200) {
                    $token = $res["data"]["access_token"];
                    $redis->set($key, $token);
                    $redis->expire($key, $res["data"]["expires_in"]);
                }
            }
            if(empty($token)) {
                throw new InvalidArgumentException("getToken fail");
            }
            return $token;
        } catch(Exception $e) {
            Log::error("hjhcloud", array($e->getMessage(), $e->getTraceAsString()));
            Log::error("getToken fail", array($e->getMessage(), $e->getTraceAsString()));
            throw $e;
        }
    }

    private function fetchRedisConnect()
    {
        return Redis::connection("device")->client();
    }
}