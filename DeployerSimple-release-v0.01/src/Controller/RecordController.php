<?php
/**
 * Record.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/7/25 16:08
 */

namespace Deploy\Controller;


use Deploy\Model\Record;

class RecordController extends Controller
{
    public function index()
    {
        $result = Record::get();
        $count = Record::count();
        $current = $_GET['page'] ?? 1;

        $this->render('index', [
            'records' => $result,
            'count' => $count,
            'current' => $current
        ]);
    }

    public function get()
    {
        $recordId = $_GET['id'];

        $record = Record::getById($recordId);

        $this->render('show', [
            'record' => $record
        ]);
    }
}