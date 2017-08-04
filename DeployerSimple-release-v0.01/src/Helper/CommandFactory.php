<?php
/**
 * CommandFactory.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/7/28 10:17
 */

namespace Deploy\Helper;


class CommandFactory
{
    /**
     * GET COMMAND OBJ BY COMMAND TYPE.
     *
     * @param $type
     *
     * @return Command
     */
    public static function getCommand($type)
    {
        switch ($type) {
            case 1:
                return new SyncHelper();
            case 2:
            default:
                return new ExecHelper();
        }
    }
}