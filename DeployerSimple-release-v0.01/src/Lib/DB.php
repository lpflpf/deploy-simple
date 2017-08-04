<?php
/**
 * DB.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/7/14 14:29
 */

namespace Deploy\Lib;


use Deploy\Config\Configure;
use SQLite3;

class DB
{
    /**
     * @var SQLite3
     */
    private static $db;

    public static function connection()
    {
        if (null === static::$db) {
            static::$db = new SQLite3(Configure::getConfig('web.server.dbFileName'));
        }
    }

    public static function query($sql, $params = [])
    {
        $res = static::exec($sql, $params);

        $rows = [];

        while($row = $res->fetchArray(SQLITE3_ASSOC)){
            $rows[] = $row;
        }
        $res->finalize();

        return $rows;
    }

    public static function exec($sql, $params = [])
    {
        static::connection();
        $stmt = static::$db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        return $stmt->execute();
    }

    public static function getLastInsertRowId(){
        return static::$db->lastInsertRowID();
    }
}