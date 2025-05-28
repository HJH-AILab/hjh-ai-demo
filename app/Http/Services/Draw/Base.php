<?php
namespace App\Http\Services\Draw;

use App\Http\Services\AbstractService;

class Base extends AbstractService {
    //0:禁用,1:启用
    const ST_OFF = 0;
    const ST_ON = 1;

    const ST_OK = 1;
    const ST_DELETE = -1;

    const NO = 0;
    const YES = 1;

    const LIST_IMAGE_WIDTH = 100;
    const LIST_IMAGE_HEIGHT = 100;

    const IMAGE_WIDTH = 200;
    const IMAGE_HEIGHT = 200;

    // 0-默认 1-好机绘
    const SOURCE_DEFAULT = 0;
    const SOURCE_HJH = 1;

    // 1:方,2:竖,3:横
    const FORMAT_UNKOWN = 0;
    const FORMAT_SQURE = 1;
    const FORMAT_PORTRAIT = 2;
    const FORMAT_LANDSCAPE = 3;

    public static $_ST = array(
        self::ST_OFF => "禁用",
        self::ST_ON => "启用",
    );

    public static $_WHETHER = array(
        self::NO => "否",
        self::YES => "是",
    );

    public static $_SOURCE = array(
        self::SOURCE_DEFAULT => "默认",
        self::SOURCE_HJH => "好机绘",
    );

    public static $_FORMAT = array(
        self::FORMAT_UNKOWN => "未知",
        self::FORMAT_SQURE => "方",
        self::FORMAT_PORTRAIT => "竖",
        self::FORMAT_LANDSCAPE => "横",
    );
}