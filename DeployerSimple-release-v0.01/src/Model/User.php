<?php
/**
 * User.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/8/2 13:51
 */

namespace Deploy\Model;


use Deploy\Lib\DB;

class User
{
    public static function isValidUser($user, $password)
    {
        $user = DB::query("SELECT * FROM User WHERE username = :user", [
            ":user" => $user
        ]);
        if (count($user) === 0) {
            return false;
        }

        $user = $user[0];

        if ($user['password'] === static::getPassword($password)) {
            return sha1($user['id']);
        }

        return false;
    }

    public static function checkSession($user, $uid){
        $user = DB::query("SELECT * FROM User WHERE username = :user", [
            ":user" => $user
        ]);
        if (count($user) === 0) {
            return false;
        }

        $user = $user[0];

        if ($uid === sha1($user['id'])) {
            return true;
        }

        return false;
    }

    private static function getPassword($password)
    {
        return sha1($password);
    }
}