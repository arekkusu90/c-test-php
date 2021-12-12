<?php

namespace Clx\Utils;

class Str
{

    public static function startsWith($str, $needle)
    {
        $length = strlen($needle);
        return substr($str, 0, $length) === $needle;
    }
}