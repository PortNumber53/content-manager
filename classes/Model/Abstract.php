<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Abstract
 */
abstract class Model_Abstract
{

    public static function getById($_id, &$options = array())
    {
        try {
            //self::_add_domain($_id);
            $cache_key = '/' . static::$_table_name . ':row:' . $_id;
            $row = Cache::instance('redis')->get($cache_key);
            if (true || empty($row)) {
                $query = DB::select()->from(static::$_table_name)->where(static::$_primary_key, '=', $_id);
                $row = $query->execute()->as_array();
                if (count($row) === 1) {
                    $row = array_shift($row);
                    $data = Arr::path($row, 'data');
                    $data = json_decode(empty($data) ? '{}' : Arr::path($row, 'data', '{}'), true);
                    unset($data['_id']);
                    $row = array_merge($row, $data);
                    unset($row['data']);
                    $extra_json = Arr::path($row, 'extra_json');
                    $extra_json = json_decode(empty($extra_json) ? '{}' : Arr::path($row, 'extra_json', '{}'), true);
                    unset($extra_json['_id']);
                    $row = array_merge($row, $extra_json);
                    unset($row['extra_json']);

                    Cache::instance('redis')->set($cache_key, json_encode($row));
                    return $row;
                } else {
                    return false;
                }
            } else {
                $row = json_decode($row, true);
            }
            return $row;
        } catch (Exception $e) {
            $error = array(
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            );
            return false;
        } finally {
            return $row;
        }
    }


    public static function _beforeSave(&$data)
    {
        return $data;
    }

    public static function save(&$data, &$error, &$options = array())
    {
        $data = static::_beforeSave($data);
        $exists = false;

        if (!isset($data[static::$_primary_key])) {
            $data[static::$_primary_key] = 0;
        }

        $update_filter = 'object_id';
        if (!empty($data['object_id'])) {
            $exists = static::getByObjectId($data['object_id']);
        }
        if (!$exists && $data[static::$_primary_key] !== 0) {
            $exists = static::getById($data[static::$_primary_key]);
            $update_filter = '_id';
        }
        if ($exists) {
            $data = array_merge($exists, $data);
        }

        $json_data = array_diff_key($data, static::$_columns);
        $data = array_intersect_key($data, static::$_columns);
        $data['json_data'] = json_encode($json_data);

        ksort($data);
        try {
            if ($exists) {
                //Update
                $data['updated_at'] = date('Y-m-d H:i:s');
                $query = DB::update(static::$_table_name)->set($data)->where(static::$_primary_key, '=',
                    $data[static::$_primary_key]);
                $result = $query->execute();
            } else {
                //Insert
                $result = DB::insert(static::$_table_name, array_keys($data))->values($data)->execute();
                $data[static::$_primary_key] = $result[0];
            }
            if (!empty($data['object_id'])) {
                $cache_key = '/' . static::$_table_name . ':row:' . $data['_id'];
                Cache::instance('redis')->delete($cache_key);
            }

            //Handle tagging
            if (!empty($json_data['tags'])) {
                $oTagged = new Model_Tagged();
                $oTag = new Model_Tag();
                //Get current tags
                $tag_array = $oTagged->get_by_associated_id($data['object_id']);
                foreach (explode(',', $json_data['tags']) as $tag) {
                    $filter = array(
                        array('tag', '=', $tag,),
                    );
                    $tag_result = $oTag->filter($filter);
                    if ($tag_result['count'] == 0) {
                        //Create tag
                        $new_tag_data = array(
                            '_id' => '/' . DOMAINNAME . '/' . URL::title($tag),
                            'tag' => $tag,
                        );
                        $result = $oTag->save($new_tag_data, $error);
                    } else {
                        $new_tag_data = $tag_result['rows'][0];
                    }
                    //Link object to tag
                    $tagged_data = array(
                        '_id' => '/' . $data['object_id'] . '/' . $new_tag_data['object_id'],
                        'object_id' => $new_tag_data['object_id'], //Tag_id
                        'associated_id' => $data['object_id'],
                    );
                    $result_tagged = $oTagged->save($tagged_data, $error);
                    foreach ($tag_array as $key => $value) {
                        if ($tag_array[$key]['_id'] == $tagged_data['_id']) {
                            unset($tag_array[$key]);
                        }
                    }
                }
                foreach ($tag_array as $key => $value) {
                    $oTagged->delete_by_id($value['_id']);
                }
            }
        } catch (Exception $e) {
            $error = array(
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            );
            print_r($error);
            return false;
        }

        $data = static::_afterSave($data);
        return $data;
    }

    public static function _afterSave(&$data)
    {
        return $data;
    }


}
