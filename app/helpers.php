<?php

use Illuminate\Support\Str;

if (!function_exists('stringable'))
{
    function stringable ($string)
    {
        return Str::of($string);
    }
}
