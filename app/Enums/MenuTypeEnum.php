<?php

namespace App\Enums;

use App\Contracts\EnumContract;

class MenuTypeEnum implements EnumContract
{
    // 'catalog','menu','button','embedded','link'
    const CATALOG = 'catalog';
    const MENU = 'menu';
    const BUTTON = 'button';
    const EMBEDDED = 'embedded';
    const LINK = 'link';

    public static function getKeys()
    {
        return [
            self::CATALOG,
            self::MENU,
            self::BUTTON,
            self::EMBEDDED,
            self::LINK,
        ];
    }

    public static function getValues()
    {
        return [
            self::CATALOG => j5_trans('目录'),
            self::MENU => j5_trans('菜单'),
            self::BUTTON => j5_trans('按钮'),
            self::EMBEDDED => j5_trans('内嵌'),
            self::LINK => j5_trans('外链'),
        ];
    }
}
