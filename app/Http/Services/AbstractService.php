<?php

namespace App\Http\Services;

class AbstractService {

    //0:禁用,1:启用
    const ST_OFF = 0;
    const ST_ON = 1;

    public static $_ST = array(
        self::ST_OFF => "禁用",
        self::ST_ON => "启用",
    );

    public static function getInstance() {
        static $_instance = NULL;
        if (empty($_instance)) {
            $_instance = new static();
        }
        return $_instance;
    }

}
