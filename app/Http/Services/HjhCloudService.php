<?php
namespace App\Http\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use InvalidArgumentException;

class HjhCloudService extends AbstractService {
    use Singleton;

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
            Log::error("hjhcloud", $e);
            Log::info("getWorkflows fail", $e->getMessage());
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
            Log::error("hjhcloud", $e);
            Log::error("getToken fail", $e->getMessage());
            throw $e;
        }
    }

    private function fetchRedisConnect()
    {
        return Redis::connection("device")->client();
    }
}