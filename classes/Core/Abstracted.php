<?php

/**
 * Class Core_Abstracted
 */
class Core_Abstracted
{

    protected static $data = array();

    public static function factory()
    {
        $obj = new static();
        $obj::$data = array();

        return $obj;
    }

}
