<?php
/**
 * ClusterController.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/6/19 17:33
 */

namespace Deploy\Controller;

use Deploy\Model\Cluster;
use Deploy\Model\IpList;

class ClusterController extends Controller
{
    public function index()
    {
        $this->render('index', [
            'clusters' => Cluster::getClusters(),
            'status' => 0
        ]);
    }

    public function delete()
    {
        if (false == isset($_POST['cluster-id']) || empty($_POST['cluster-id'])) {
            $this->render('index', [
                'clusters' => Cluster::getClusters(),
                'status' => 1,
                'message' => '删除失败，未选择集群',
            ]);
            return;
        }
        $clusterId = $_POST['cluster-id'];

        Cluster::deleteCluster($clusterId);
        IpList::deleteIpList($clusterId);
        $this->render('index', [
            'clusters' => Cluster::getClusters(),
            'status' => 1,
            'message' => "成功删除集群"
        ]);
    }

    public function add()
    {
        $ipList = $_POST['ip-list'] ?? '';
        $clusterName = $_POST['cluster-name'] ?? '';
        if (false === Cluster::hasCluster($clusterName)) {
            $clusterId = Cluster::addCluster($clusterName);
            IpList::addIpList($clusterId, $ipList);
            $status = 1;
            $message = '添加集群成功';
        } else {
            $status = 2;
            $message = '添加集群失败,已有该集群';
        }

        $this->render('index', [
            'status' => $status,
            'message' => $message,
            'clusters' => Cluster::getClusters(),
        ]);
    }

    public function getIpList()
    {
        $clusterId = $_GET['cluster_id'] ?? '';
        $this->renderJSON(0, '', IpList::getIpList($clusterId));
    }

    public function update()
    {
        $clusterId = $_POST['cluster-id'];
        if (false == isset($_POST['cluster-id']) || empty($_POST['cluster-id'])) {
            $this->render('index', [
                'clusters' => Cluster::getClusters(),
                'status' => 1,
                'message' => '删除失败，未选择集群',
            ]);
            return;
        }
        $ipList = $_POST['ip-list'] ?? '';
        IpList::deleteIpList($clusterId);
        IpList::addIpList($clusterId, $ipList);

        $this->render('index', [
            'status' => 3,
            'message' => '更新集群成功',
            'clusters' => Cluster::getClusters()
        ]);
    }

    public function hasClusterName()
    {
        $clusterName = $_GET['cluster_name'] ?? '';
        $cluster = new Cluster();
        $result = $cluster->hasCluster($clusterName);

        $this->renderJSON(0, '', $result);
    }
}