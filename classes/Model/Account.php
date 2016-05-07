<?php

/**
 * Class Model_Account
 */
class Model_Account extends Model_Abstract
{

    protected static $_table_name = 'account';
    protected static $_primary_key = '_id';

    protected static $_columns = array(
        '_id' => '',
        'profile' => '',
        'username' => '',
        'password' => '',
        'display_name' => '',
        'hash' => '',
        'created_at' => '',
        'updated_at' => '',
        'last_login' => '',
        'activation' => '',
        'json_data' => '',
    );

    protected static $_json_columns = array();

    public static function _beforeSave(&$data)
    {
        $exists = static::getAccountByUsername($data['username']);
        if ($exists) {

        } else {

        }
        $data[self::$_primary_key] = '/dev.truvis.co/' . $data['username'];

        return $data;
    }


    public static function getAccountByUsername($username)
    {
        $cache_key = '/' . static::$_table_name . ':row:' . $username;
        //echo " CACHE: $cache_key<br>";
        $row = Cache::instance('redis')->get($cache_key);
        if (true || empty($row)) {
            $query = DB::select()->from(static::$_table_name)->where('username', '=', $username);
            $row = $query->execute()->as_array();
            //print_r($row);
            if (count($row) == 1) {
                $row = array_shift($row);
                $data = json_decode(empty(Arr::path($row, 'data')) ? '{}' : Arr::path($row, 'data', '{}'), true);
                unset($data['_id']);
                $row = array_merge($row, $data);
                unset($row['data']);
                $json_data = json_decode(empty(Arr::path($row, 'json_data')) ? '{}' : Arr::path($row, 'json_data',
                    '{}'), true);
                unset($json_data['_id']);
                $row = array_merge($row, $json_data);
                unset($row['json_data']);

                Cache::instance('redis')->set($cache_key, json_encode($row));
            } else {
                return false;
            }
        } else {
            $row = json_decode($row, true);
        }
        return $row;
    }


}
