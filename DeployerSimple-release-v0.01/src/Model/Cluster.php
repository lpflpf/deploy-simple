<?php

namespace Deploy\Model;

use Deploy\Lib\DB;

/**
 * Cluster.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/6/20 15:45
 */
class Cluster
{
    public static function getClusters()
    {
        $list = DB::query("SELECT cluster.id, cluster.name FROM cluster WHERE is_delete = 0");
        return $list;
    }

    public static function addCluster($cluster)
    {
        DB::exec('INSERT INTO cluster(name,is_delete,creation_date, last_changed_date) VALUES(:name,0, datetime(),datetime())', [
            ':name' => $cluster
        ]);

        return DB::getLastInsertRowId();
    }

    /**
     * @param $clusterId
     *
     * @return bool
     */
    public static function deleteCluster($clusterId)
    {
        return false != DB::exec("UPDATE cluster SET is_delete = 1, last_changed_date = datetime() WHERE id = :cluster_id",
                [
                    ":cluster_id" => $clusterId
                ]);
    }

    public static function hasCluster($clusterName)
    {
        $result = DB::query("SELECT count(*) AS num FROM cluster WHERE name=:cluster_name",
            [
                ":cluster_name" => $clusterName
            ]);

        return $result[0]['num'] > 0;
    }

    public static function getCluster($clusterId)
    {
        return DB::query("SELECT count(*) FROM cluster WHERE id = :cluster_id", [
            ':cluster_id' => $clusterId
        ]);
    }
}