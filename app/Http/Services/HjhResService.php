<?php
namespace App\Http\Services;

use App\Hjh\AI\Client as AIClient;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use InvalidArgumentException;

class HjhResService extends AbstractService {
    use Singleton;

     //1：合规，10：不合规，11：疑似，16：审核失败
    const CONCLUSION_TYPE_OK = 1;
    const CONCLUSION_TYPE_NO = 10;
    const CONCLUSION_TYPE_SUSPECTED = 11;
    const CONCLUSION_TYPE_FAIL = 16;

    public static $_CONCLUSION_TYPE = array(
        self::CONCLUSION_TYPE_OK => "合规",
        self::CONCLUSION_TYPE_NO => "不合规",
        self::CONCLUSION_TYPE_SUSPECTED => "疑似",
        self::CONCLUSION_TYPE_FAIL => "审核失败",
    );

    /**
     * imageReview
     */
    public function imageReview($image, $imageUrl = "") {
        try {
            $params = array(
                "image_url" => $imageUrl,
                "collectionName" => "celebrity",
                "confidenceThreshold" => "50",
                "faceScoreThreshold" => "0",
                "limit" => "5",
                "maxFaceNum" => "5",
                "namespace" => "hjh"
            );
            Log::info("hjhres", $params);
            $base64 = HjhImageService::getImageBase64($image);
            $params["imageBase64"] = $base64;
            $token = $this->getToken();
            $res = \App\Hjh\AI\Client::getCallbackHttp()->withHeaders([
                'Authorization' => "Bearer $token",
                ])
                ->get(env("HJHRES_URL") . "/api/openapi/imageReview/check", $params)->json();
            Log::info("hjhres", array(
                "params" => $params,
                "token" => $token,
                "res" => $res,
            ));
            /**
             * "reason": "政治人物",
		"conclusion_type": 10,
		"conclusion": "不合规",
		"face_index": 0,
             */
            if($res && $res["code"] == 200) {
                $conclusionType = $res["data"]["conclusion_type"];
                if($conclusionType == self::CONCLUSION_TYPE_OK) {
                    return $res["data"];
                } else {
                    if($res["data"]["reason"] == "nsfw") {
                        throw new InvalidArgumentException("不合规，图片包含NSFW内容");
                    } else {
                        throw new InvalidArgumentException("不合规，图片包含敏感内容");
                    }
                }
            } else {
                throw new InvalidArgumentException($res["message"]);
            }
        } catch(Exception $e) {
            Log::error("hjhres", array($e->getMessage(), $e->getTraceAsString()));
            Log::info("getResCategoryList fail", array($e->getMessage(), $e->getTraceAsString()));
            return array();
        }
    }

    /**
     * getResCategoryList
     */
    public function getResCategoryList() {
        try {
            $request = request();
            $start = (int) $request->input('start', 0);
            $limit = (int) $request->input('limit', 10);

            $params = array(
                "start" => 0,
                "limit" => 10,
            );
            $token = $this->getToken();
            $res = \App\Hjh\AI\Client::getCallbackHttp()->withHeaders([
                'Authorization' => "Bearer $token",
                ])
                ->get(env("HJHRES_URL") . "/api/openapi/resCategory/getList", $params)->json();
            Log::info("hjhres", array(
                "params" => $params,
                "token" => $token,
                "res" => $res,
            ));
            if($res && $res["code"] == 200) {
                $list = $res["data"]["list"];
                return $list;
            } else {
                throw new InvalidArgumentException($res["message"]);
            }
        } catch(Exception $e) {
            Log::error("hjhres", array($e->getMessage(), $e->getTraceAsString()));
            Log::info("getResCategoryList fail", array($e->getMessage(), $e->getTraceAsString()));
            return array();
        }
    }

    /**
     * getResFilesList
     */
    public function getResFilesList() {
        try {
            $request = request();
            $start = (int) $request->input('start', 0);
            $limit = (int) $request->input('limit', 10);

            $params = array(
                "start" => 0,
                "limit" => 10,
            );
            $token = $this->getToken();
            $res = \App\Hjh\AI\Client::getCallbackHttp()->withHeaders([
                'Authorization' => "Bearer $token",
                ])
                ->get(env("HJHRES_URL") . "/api/openapi/resFiles/getList", $params)->json();
            Log::info("hjhres", array(
                "params" => $params,
                "token" => $token,
                "res" => $res,
            ));
            if($res && $res["code"] == 200) {
                $list = $res["data"]["list"];
                return $list;
            } else {
                throw new InvalidArgumentException($res["message"]);
            }
        } catch(Exception $e) {
            Log::error("hjhres", array($e->getMessage(), $e->getTraceAsString()));
            Log::info("getResFilesList fail", array($e->getMessage(), $e->getTraceAsString()));
            return array();
        }
    }

    public function getAllResCategory() {
        try {
            $token = $this->getToken();
            $res = \App\Hjh\AI\Client::getCallbackHttp()->withHeaders([
                'Authorization' => "Bearer $token",
                ])
                ->get(env("HJHRES_URL") . "/api/openapi/resCategory/getAll")->json();
            Log::info("hjhres", array(
                "token" => $token,
                "res" => $res,
            ));
            if($res && $res["code"] == 200) {
                $list = $res["data"]["list"];
                return $list;
            } else {
                throw new InvalidArgumentException($res["message"]);
            }
        } catch(Exception $e) {
            Log::error("hjhres", array($e->getMessage(), $e->getTraceAsString()));
            Log::info("getAllResCategory fail", array($e->getMessage(), $e->getTraceAsString()));
            return array();
        }
    }

    public function getAllResAlbum() {
        try {
            $token = $this->getToken();
            $res = \App\Hjh\AI\Client::getCallbackHttp()->withHeaders([
                'Authorization' => "Bearer $token",
                ])
                ->get(env("HJHRES_URL") . "/api/openapi/resAlbum/getAll")->json();
            Log::info("hjhres", array(
                "token" => $token,
                "res" => $res,
            ));
            if($res && $res["code"] == 200) {
                $list = $res["data"]["list"];
                return $list;
            } else {
                throw new InvalidArgumentException($res["message"]);
            }
        } catch(Exception $e) {
            Log::error("hjhres", array($e->getMessage(), $e->getTraceAsString()));
            Log::info("getAllResAlbum fail", array($e->getMessage(), $e->getTraceAsString()));
            return array();
        }
    }

    public function getAllResTag() {
        try {
            $token = $this->getToken();
            $res = \App\Hjh\AI\Client::getCallbackHttp()->withHeaders([
                'Authorization' => "Bearer $token",
                ])
                ->get(env("HJHRES_URL") . "/api/openapi/resTag/getAll")->json();
            Log::info("hjhres", array(
                "token" => $token,
                "res" => $res,
            ));
            if($res && $res["code"] == 200) {
                $list = $res["data"]["list"];
                return $list;
            } else {
                throw new InvalidArgumentException($res["message"]);
            }
        } catch(Exception $e) {
            Log::error("hjhres", array($e->getMessage(), $e->getTraceAsString()));
            Log::info("getAllResTag fail", array($e->getMessage(), $e->getTraceAsString()));
            return array();
        }
    }

    public function getResRandomAlbumFile() {
        try {
            $request = request();
            $albumId = (int) $request->input('album_id', 0);
            $num = (int) $request->input('num', 10);
            $params = array(
                "album_id" => $albumId,
                "num" => $num,
            );
            $token = $this->getToken();
            $res = \App\Hjh\AI\Client::getCallbackHttp()->withHeaders([
                'Authorization' => "Bearer $token",
                ])
                ->get(env("HJHRES_URL") . "/api/openapi/resAlbumFile/getRandomFile", $params)->json();
            Log::info("hjhres", array(
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
            Log::error("hjhres", array($e->getMessage(), $e->getTraceAsString()));
            Log::info("getResRandomAlbumFile fail", array($e->getMessage(), $e->getTraceAsString()));
            return array();
        }
    }

    public function getToken() {
        try {
            $redis = $this->fetchRedisConnect();
            $key = "res:openapi:token";
            $token = $redis->get($key);
            if(empty($token)) {
                $url = env("HJHRES_URL") . "/api/openapi/login/getToken";
                $params = array(
                    "client_id" => env("HJHRES_CLIENT_ID"),
                    "client_secret" => env("HJHRES_CLIENT_SECRET"),
                );
                $res = \App\Hjh\AI\Client::getCallbackHttp()
                    ->post($url, $params)->json();
                Log::info("hjhres", array(
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
            Log::error("hjhres", array($e->getMessage(), $e->getTraceAsString()));
            Log::info("getToken fail", array($e->getMessage(), $e->getTraceAsString()));
            throw $e;
        }
    }

    private function fetchRedisConnect()
    {
        return Redis::connection("device")->client();
    }
}