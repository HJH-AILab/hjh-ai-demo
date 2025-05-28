<?php
namespace App\Http\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use InvalidArgumentException;

class HjhImageService extends AbstractService {
    use Singleton;

    public static function getImageBase64($imageUrl) {
        $type = getimagesize($imageUrl); //取得图片的大小，类型等
        $content = file_get_contents($imageUrl); //获取图片的内容
        //已经将图片转成base64编码
        $file_content = base64_encode($content);

        switch ($type[2]) { //判读图片类型
            case 1:
                $img_type = "gif";
                break;
            case 2:
                $img_type = "jpg";
                break;
            case 3:
                $img_type = "png";
                break;
        }

        //合成图片的base64编码,方便在页面中展示
        $img = 'data:image/' . $img_type . ';base64,' . $file_content;
        return $img;
    }
}