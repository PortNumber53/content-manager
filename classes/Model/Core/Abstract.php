<?php defined('SYSPATH') or die('No direct script access.');

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

    protected abstract static function save(&$data, &$error, &$options = array());

    protected static function saveRow($data, &$error = array())
    {
        return static::_saveRow($data, $error);
    }

}
