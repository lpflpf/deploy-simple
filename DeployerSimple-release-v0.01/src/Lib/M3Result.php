<?php
/**
 * M3Result.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/7/13 13:58
 */

namespace Deploy\Lib;


use JsonSerializable;

class M3Result implements JsonSerializable
{
    public function toJson()
    {
        return json_encode($this);
    }

    public $status = 0;
    public $message = '';
    public $data = [];

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return $this;
    }
}