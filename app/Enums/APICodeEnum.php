<?php

namespace App\Enums;

use App\Contracts\EnumContract;

class APICodeEnum implements EnumContract
{
    const SUCCESS = 0;
    const EXCEPTION = -1;

    public static function getKeys()
    {
        return [
            self::SUCCESS,
            self::EXCEPTION,
        ];
    }

    public static function getValues()
    {
        return [
            self::SUCCESS => __('成功'),
            self::EXCEPTION => __('异常'),
        ];
    }
}
