<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 11/8/2015
 * Time: 12:45 PM
 */

/**
 * Class Model_Abstract
 */
abstract class Model_Abstract extends Model_Core_Abstract
{

    public function save(&$data, &$error, &$options = array())
    {
        $this->_before_save($data);
        $exists = false;

        if (!isset($data[static::$_primary_key])) {
            $data[static::$_primary_key] = 0;
        }

        $update_filter = 'object_id';
        if (!empty($data['object_id'])) {
            $exists = $this->get_by_object_id($data['object_id']);
        }
        if (!$exists && $data[static::$_primary_key] !== 0) {
            $exists = $this->get_by_id($data[static::$_primary_key]);
            $update_filter = '_id';
        }
        if ($exists) {
            $data = array_merge($exists, $data);
        }

        $json_data = array_diff_key($data, $this::$_columns);
        $data = array_intersect_key($data, $this::$_columns);
        $data['extra_json'] = json_encode($json_data);

        ksort($data);
        try {
            if ($exists) {
                //Update
                $data['updated_at'] = date('Y-m-d H:i:s');
                $query = DB::update($this::$_table_name)->set($data)->where(static::$_primary_key, '=',
                    $data[static::$_primary_key]);
                $result = $query->execute();
            } else {
                //Insert
                $result = DB::insert($this::$_table_name, array_keys($data))->values($data)->execute();
                $data[static::$_primary_key] = $result[0];
            }
            if (!empty($data['object_id'])) {
                $cache_key = '/' . $this::$_table_name . ':row:' . $data['_id'];
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
        return $result;
    }

}