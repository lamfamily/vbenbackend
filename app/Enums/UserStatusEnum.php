<?php

namespace App\Enums;

use App\Contracts\EnumContract;

class UserStatusEnum implements EnumContract
{
    const ENABLED = 1; // 启用
    const DISABLED = 0; // 禁用

    public static function getKeys()
    {
        return [
            self::ENABLED,
            self::DISABLED,
        ];
    }

    public static function getValues()
    {
        return [
            self::ENABLED => __('启用'),
            self::DISABLED => __('禁用'),
        ];
    }
}
