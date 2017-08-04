<?php
/**
 * Record.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/7/25 11:15
 */

namespace Deploy\Model;


use Deploy\Lib\DB;
use Deploy\Lib\Tools;

class Record
{
    public static function set($batchId, $execIp, $command, $result, $execStatus, $execTime)
    {
        return DB::exec("INSERT INTO exec_record (batch_id, exec_ip, user_ip, cmd, result, exec_status, exec_time, creation_date, last_changed_date) 
                      VALUES(:batch_id, :exec_ip,:user_ip, :cmd, :result,:exec_status, :exec_time, datetime(),datetime())",
            [
                ":batch_id" => $batchId,
                ":exec_ip" => $execIp,
                ":user_ip" => Tools::getCustomerAddress(),
                ":cmd" => $command,
                ":result" => $result,
                ":exec_status" => $execStatus,
                ":exec_time" => $execTime
            ]);
    }

    public static function get()
    {
        $page = $_GET['page'] ?? 1;

        $sql = 'SELECT * FROM exec_record ORDER BY last_changed_date DESC LIMIT ' . (($page - 1) * static::$pageSize) . ',' . static::$pageSize;
        return DB::query($sql);
    }

    public static function getById($id)
    {
        $row = DB::query("SELECT * FROM exec_record WHERE id = :id", [
            ':id' => $id
        ]);

        return current($row);
    }

    public static function count()
    {
        $result = DB::query("SELECT count(*) AS cnt FROM exec_record");

        return current($result)['cnt'];
    }

    private static $pageSize = 10;
}