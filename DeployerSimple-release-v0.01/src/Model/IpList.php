<?php
/**
 * IpList.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/6/21 19:38
 */

namespace Deploy\Model;


use Deploy\Lib\DB;

class IpList
{
    public static function getIpList($clusterId)
    {
        $ipList = DB::query('SELECT ip FROM server WHERE cluster_id = :cluster_id AND is_delete = 0', [
            ':cluster_id' => $clusterId
        ]);

        $result = [];
        foreach ($ipList as $row) {
            $result[] = $row['ip'];
        }
        return $result;
    }

    public static function addIpList($clusterId, $ipLists)
    {
        $ipLists = static::formatIpList($ipLists);
        foreach ($ipLists as $ip) {
            DB::exec('INSERT INTO server(cluster_id, ip, creation_date, last_changed_date) VALUES(:cluster_id, :ip, datetime(), datetime())', [
                ':cluster_id' => $clusterId,
                ':ip' => $ip
            ]);
        }
    }

    public static function formatIpList($ipLists)
    {
        $ipLists = preg_replace('/(\r\n|\n|,|\s)+/', ',', $ipLists);
        $ipLists = trim($ipLists, ',');
        return explode(',', $ipLists);
    }

    public static function deleteIpList($clusterId)
    {
        return DB::exec('UPDATE server SET is_delete = 1, last_changed_date = datetime() WHERE cluster_id = :cluster_id', [
            ':cluster_id' => $clusterId
        ]);
    }
}