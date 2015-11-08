<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 11/8/2015
 * Time: 12:45 PM
 */

/**
 * Class Model_Account
 */
class Model_Account extends Model_Abstract
{

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
                $extra_json = json_decode(empty(Arr::path($row, 'extra_json')) ? '{}' : Arr::path($row, 'extra_json',
                    '{}'), true);
                unset($extra_json['_id']);
                $row = array_merge($row, $extra_json);
                unset($row['extra_json']);

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