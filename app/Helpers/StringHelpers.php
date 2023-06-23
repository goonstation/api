<?php

if (! function_exists('ckey')) {
    function ckey($val)
    {
        if (! is_string($val)) {
            return null;
        }

        return preg_replace('/[^a-z\d]/i', '', strtolower($val));
    }
}

if (! function_exists('migval')) { // stands for migration value ok
    function migval($val)
    {
        return $val === "\N" ? null : $val;
    }
}
