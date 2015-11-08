<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 11/8/2015
 * Time: 12:42 PM
 */

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
