<?php

namespace NetTeam\Bundle\DataTableBundle\Util;

class String
{
    public static function toAscii($text)
    {
        $tr = array(
        // Polish
            'ą'=>'a','ć'=>'c','ę'=>'e','ł'=>'l','ń'=>'n','ó'=>'o','ś'=>'s','ż'=>'z','ź'=>'z',
            'Ą'=>'A','Ć'=>'C','Ę'=>'E','Ł'=>'L','Ń'=>'N','Ó'=>'O','Ś'=>'S','Ż'=>'Z','Ź'=>'Z',
        // Swedish
            'ä'=>'a','ĺ'=>'a','ö'=>'o',
            'Ä'=>'A','Ĺ'=>'A','Ö'=>'O',
        // Germany
            'ü'=>'u',
            'Ü'=>'U'
        );
        return strtr($text, $tr);
    }

    public static function toLower($string)
    {
        if (function_exists('mb_strtolower')) {
            $lower = mb_strtolower($string, mb_detect_encoding($string));
        } else {
            $lower = strtolower($string);
        }

        return $lower;
    }

    public static function toUpper($string)
    {
        if (function_exists('mb_strtoupper')) {
            $lower = mb_strtoupper($string, mb_detect_encoding($string));
        } else {
            $lower = strtoupper($string);
        }

        return $lower;
    }
}
