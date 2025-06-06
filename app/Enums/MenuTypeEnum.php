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
            self::CATALOG => __('目录'),
            self::MENU => __('菜单'),
            self::BUTTON => __('按钮'),
            self::EMBEDDED => __('内嵌'),
            self::LINK => __('外链'),
        ];
    }
}
