<?php
namespace App\Http\Services\Draw;

use App\Http\Services\AbstractService;

class Task extends Base {
    //1:创建,2:执行中,3:执行完成,10:执行失败
    const TASK_STATUS_CREATE = 1;
    const TASK_STATUS_DOING = 2;
    const TASK_STATUS_DONE = 3;
    const TASK_STATUS_FAIL = 10;
    

    //1:创建,2:执行中,3:执行完成,10:执行失败
    public static $_TASK_STATUS = array(
        self::TASK_STATUS_CREATE => "创建",
        self::TASK_STATUS_DOING => "执行中",
        self::TASK_STATUS_DONE => "执行完成",
        self::TASK_STATUS_FAIL => "执行失败",
    );
}