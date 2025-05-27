<?php

namespace App\Http\Services;

trait Singleton
{
    private static $instance;
    static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}
