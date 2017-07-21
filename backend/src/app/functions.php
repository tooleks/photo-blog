<?php

namespace App;

if (!function_exists('xss_protect')) {
    /**
     * @param string $string
     * @return string
     */
    function xss_protect($string)
    {
        return htmlspecialchars($string, ENT_NOQUOTES);
    }
}
