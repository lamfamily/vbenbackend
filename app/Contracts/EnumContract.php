<?php

namespace App\Contracts;

interface EnumContract
{
    public static function getKeys();

    public static function getValues();
}
