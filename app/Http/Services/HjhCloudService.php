<?php
namespace App\Http\Services;

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