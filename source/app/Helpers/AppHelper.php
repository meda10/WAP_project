<?php

namespace App\Helpers;

use App\Models\Discount;

class AppHelper
{
    public static function friendlyUrl($name) {
        $url = $name;
        
        $accentedChars = ['Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'č' => 'c', 'Č' => 'c', 'ů' => 'u', 'ě' => 'e', 'Ů' => 'u', 
                            'Ě' => 'e', 'ř' => 'r', 'Ř' => 'r'];

        foreach ($accentedChars as $key => $value)
            $url = str_replace($key, $value, $url);
        
        $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
        $url = trim($url, "-");
        $url = strtolower($url);
        $url = preg_replace('~[^-a-z0-9_]+~', '', $url);
        return $url;
    }

    public static function generateDiscountCode()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        do {
            $code = '';
            for ($i = 0; $i < 10; $i++) {
                $type = rand(0, 1);
                
                if ($type) {
                    $nextChar = strval(rand(0, 9));
                } else {
                    $nextChar = $chars[rand(0, strlen($chars) - 1)];
                }

                $code .= $nextChar;
            }

            $generate = Discount::checkCodeNotExist($code);
        } while ($generate);

        return $code;
    }
}
