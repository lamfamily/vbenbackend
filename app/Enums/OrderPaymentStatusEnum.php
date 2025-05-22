<?php

namespace App\Enums;

use App\Contracts\EnumContract;

class OrderPaymentStatusEnum implements EnumContract
{
    const PAID = 1; // 已支付
    const UNPAID = 0; // 未支付

    public static function getKeys()
    {
        return [
            self::PAID,
            self::UNPAID,
        ];
    }

    public static function getValues()
    {
        return [
            self::PAID => j5_trans('已支付'),
            self::UNPAID => j5_trans('未支付'),
        ];
    }
}
