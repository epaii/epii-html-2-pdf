<?php
/**
 * Created by PhpStorm.
 * User: mrren
 * Date: 2019/6/28
 * Time: 1:26 PM
 */

namespace epii\sign;

class sign
{
    public static function encode(&$data, $secret_key)
    {

        if (is_array($data)) {
            asort($data);
            $string = "";
            foreach ($data as $key => $value) {
                $string .= $key . "=" . $value . "&";
            }

            $data["sign"] = md5($string . $secret_key);
            return $data["sign"];

        }
        return null;
    }


    public static function check($data, $secret_key)
    {

        if (is_array($data)) {
            if (!isset($data["sign"])) {
                return false;
            }
            $sign = $data["sign"];
            unset($data["sign"]);

            self::encode($data, $secret_key);
            if ($data["sign"] == $sign) {
                return true;
            }


        }
        return false;
    }

}