<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 11/7/2015
 * Time: 8:07 PM
 */

class Website
{
    public static $template_name = '';
    protected static $settings = array();


    static function load_settings()
    {
        self::$settings = Kohana::$config->load('website')->as_array();
    }

    public static function get($path, $default=NULL)
    {
        if (empty(self::$template_file))
        {
            //echo 'Empty template_file; ';
            //self::set_template();
        }
        if (empty(self::$settings))
        {
            //echo 'load settings ';
            self::load_settings();
        }
        return Arr::path(self::$settings, $path, $default);
    }

    public static function set_template($name)
    {
        self::$template_name = $name;
        View::$template_name = $name;
    }
}