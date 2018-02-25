<?php

namespace App\Vendor\Pay;

use Illuminate\Support\Facades\Log;

class Utils
{
    const HEXMAP = [
        '0' => 'P',
        '1' => 'n',
        '2' => 'y',
        '3' => '6',
        '4' => 'B',
        '5' => '9',
        '6' => 'R',
        '7' => '7',
        '8' => '2',
        '9' => 'A',
        'A' => 'e',
        'B' => 'G',
        'C' => '5',
        'D' => 'm',
        'E' => 'T',
        'F' => '8',
        ':' => 'a',
    ];

    public static function encryptID($id, $prefix = '', $timestamp = null)
    {
        $id_hex = dechex($id);
        if (!is_null($timestamp)) {
            $timestamp = strtotime($timestamp);
            $id_hex .= $timestamp;
        }
        
        $ids = str_split(strtoupper($id_hex));
        if (count($ids) < 14) {
            $ids = array_merge($ids, [':'], static::padding($id, 14 - count($ids)));
        }
        $id_new = [];
        foreach ($ids as $value) {
            $id_new[] = static::HEXMAP[$value];
        }

        return $prefix . '_' . implode('', $id_new);
    }

    public static function decryptID($id, $prefix = null)
    {
        $hashed_id = static::getHashedIdWithoutPrefix($id, $prefix);
        if ($hashed_id) {
            $ids = str_split($hashed_id);
            $id_new = [];
            $hexmap = array_flip(static::HEXMAP);
            foreach ($ids as $value) {
                $temp = $hexmap[$value];
                if (':' == $temp) {
                    break;
                }
                $id_new[] = $temp;
            }
            $id = implode($id_new);
            return hexdec($id);
        }

        return null;
    }

    public static function isHashedId($id)
    {
        return is_string($id) && strpos($id, '_') > 0;
    }

    public static function getHashedIdWithoutPrefix($id, $prefix = null)
    {
        $hashed_id = null;
        if (is_string($id)) {
            $lenth = strlen($id);
            $pos = strpos($id, '_');
            if ($pos > 0 && $lenth - $pos > 10) {
                Log::info("$prefix==" . substr($id, 0, $pos));
                if (is_null($prefix) || substr($id, 0, $pos) == $prefix) {
                    $hashed_id = substr($id, $pos + 1, $lenth - $pos);
                }
            }
        }

        Log::info("hashed_id=$hashed_id");
        return $hashed_id;
    }

    public static function getRealId($id, $prefix)
    {
        return static::isHashedId($id) ? static::decryptID($id, $prefix): $id;
    }



    public static function class_namespace($object)
    {
        $class_name = get_class($object);
        return !$class_name ?: substr($class_name, 0, strrpos($class_name, '\\'));
    }
}
