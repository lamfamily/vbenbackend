<?php

namespace App\Enums;

class OrderStatusEnum extends DefaultStatusEnum
{
    const COMPLETED = 2;

    public static function getKeys()
    {
        return array_merge(parent::getKeys(), [
            self::COMPLETED,
        ]);
    }

    public static function getValues()
    {
        return [
            self::ENABLED => j5_trans('待处理'), // pending
            self::DISABLED => j5_trans('已取消'),
            self::DELETED => j5_trans('已删除'),
            self::COMPLETED => j5_trans('已完成'),
        ];
    }

}
