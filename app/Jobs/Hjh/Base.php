<?php

namespace App\Jobs\Hjh;

class Base
{
    //use Singleton;

    public $api;
    public $reserveApi;

    public static function getInstance()
    {
        static $_instance = NULL;
        if (empty($_instance)) {
            $_instance = new static();
        }
        return $_instance;
    }

    private static $_instance;
    public static function getInstance1()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public function process($task, $queue)
    {
    }

    public function process_with_callback($task, $queue)
    {
    }
}
