<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 11/8/2015
 * Time: 12:45 PM
 */

/**
 * Class Model_Core_Abstract
 */
abstract class Model_Core_Abstract extends Model_Database
{

    protected static $_table_name = 'CUSTOMIZE_TABLE_NAME';
    protected static $_primary_key = 'CUSTOMIZE_PRIMARY_KEY_NAME';

    protected static $_columns = array(
        'CUSTOMIZE_COLUMN_NAMES' => 'YOU_CAN_LEAVE_THIS_EMPTY',
    );
    

    abstract public function save(&$data, &$error, &$options = array());

    public static function saveRow($data, &$error = array())
    {
        return static::_saveRow($data, $error);
    }

    public static function _saveRow($data, &$error = array())
    {
        return true;
    }
}