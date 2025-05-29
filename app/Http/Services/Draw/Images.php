<?php
namespace App\Http\Services\Draw;

use App\Http\Services\AbstractService;

class Images extends Base {
    //1-用户 2-AIGC
    const TYPE_USER = 1;
    const TYPE_AIGC = 2;
    
    //1-用户 2-AIGC
    public static $_TASK_STATUS = array(
        self::TYPE_USER => "用户",
        self::TYPE_AIGC => "AIGC",
    );
}