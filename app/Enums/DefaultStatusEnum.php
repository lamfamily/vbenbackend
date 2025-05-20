<?php

namespace App\Enums;

class DefaultStatusEnum
{
    const ENABLED = 1; // 启用
    const DISABLED = 0; // 禁用
    const DELETED = -1; // 删除

    public static function getKeys()
    {
        return [
            self::ENABLED,
            self::DISABLED,
            self::DELETED,
        ];
    }

    public static function getValues()
    {
        return [
            self::ENABLED => j5_trans('已启用'),
            self::DISABLED => j5_trans('已禁用'),
            self::DELETED => j5_trans('已删除'),
        ];
    }
}
