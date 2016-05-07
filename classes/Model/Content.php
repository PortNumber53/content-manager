<?php

/**
 * Class Model_Content
 */
class Model_Content extends Model_Abstract
{

    protected static $_table_name = 'content';
    protected static $_primary_key = '_id';

    protected static $_columns = array(
        '_id' => '',
        'title' => '',
        'type' => '',
        'og_tags' => '',
        'open_graph' => '',
        'description' => '',
        'content' => '',
        'keywords' => '',
        'facebook_id' => '',
        'ip' => '',
        'first_published' => '',
        'last_updated' => '',
        'views' => '',
        'up' => '',
        'version' => '',
        'data' => '',
        'created_at' => '',
        'updated_at' => '',
    );

    protected static $_json_columns = array();

    public static function getRowByUrl($url)
    {
        $cache_key = '/' . static::$_table_name . ':url:' . $url;
        //echo " CACHE: $cache_key<br>";
        $row = Cache::instance('redis')->get($cache_key);
        if (true || empty($row)) {
            $query = DB::select()->from(static::$_table_name)->where('url', '=', $url);
            $row = $query->execute()->as_array();
            //print_r($row);
            if (count($row) == 1) {
                $row = array_shift($row);
                $data = json_decode(Arr::path($row, 'data', '{}'), true);
                unset($data['_id']);
                $row = array_merge($row, $data);
                unset($row['data']);
                $json_data = json_decode(Arr::path($row, 'json_data', '{}'), true);
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

    public static function incrementViewCount($_id)
    {
        $query = DB::update(static::$_table_name)->
        set(array('views' => DB::expr('views + 1')))->
        where(static::$_primary_key, '=', $_id);
        $result = $query->execute();

        return $result;
    }

    public static function getMostVisited($number = 20)
    {
        $cache_key = '/' . static::$_table_name . ':most_visited';
        //echo " CACHE: $cache_key<br>";
        $result = Cache::instance('redis')->get($cache_key);
        if (true || empty($row)) {
            $query = DB::select()->from(static::$_table_name)->limit($number)->order_by('views', 'desc');
            $result = $query->execute()->as_array();
            //print_r($row);
            if (count($result) > 0) {
                foreach ($result as $key => $row) {
                    //$row = array_shift($row);
                    $data = json_decode(Arr::path($row, 'data', '{}'), true);
                    unset($data['_id']);
                    $row = array_merge($row, $data);
                    unset($row['data']);
                    $json_data = json_decode(Arr::path($row, 'json_data', '{}'), true);
                    unset($json_data['_id']);
                    $row = array_merge($row, $json_data);
                    unset($row['json_data']);
                    $result[$key] = $row;
                }
                Cache::instance('redis')->set($cache_key, json_encode($result));
            } else {
                return false;
            }
        } else {
            $result = json_decode($result, true);
        }
        return $result;
    }

    public static function getRandom($number = 20)
    {
        $query = DB::select()->from(static::$_table_name)->limit($number)->order_by(DB::expr('RAND()'));
        $result = $query->execute()->as_array();
        //print_r($row);
        if (count($result) > 0) {
            foreach ($result as $key => $row) {
                //$row = array_shift($row);
                $data = json_decode(Arr::path($row, 'data', '{}'), true);
                unset($data['_id']);
                $row = array_merge($row, $data);
                unset($row['data']);
                $json_data = json_decode(Arr::path($row, 'json_data', '{}'), true);
                unset($json_data['_id']);
                $row = array_merge($row, $json_data);
                unset($row['json_data']);
                $result[$key] = $row;
            }
        } else {
            return false;
        }
        return $result;
    }

}
