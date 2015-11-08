<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 11/7/2015
 * Time: 8:03 PM
 */

/**
 * Class Controller_Core_Website
 */
class Controller_Core_Website extends Controller_Template
{
    public $template_name = '';
    public static $template_file = 'frontend';

    public static $settings = array();


    public function __construct(Request $request, Response $response)
    {

        $dotSettings = defined(WEBSITE) ? array() : json_decode(WEBSITE, true);

        $settings = Kohana::$config->load('website')->as_array();
        self::$settings = array_merge($settings, $dotSettings);
        View::set_global('debug', Arr::path(self::$settings, 'debug', false));

        //$this->frontend_cookie = json_decode(Cookie::get(Constants::FE_COOKIE), true);
        //$this->backend_cookie = json_decode(Cookie::get(Constants::BE_COOKIE), true);

        parent::__construct($request, $response);
    }


    public function before()
    {
        if (empty($this->template_name)) {
            // Old config format template.selected is a string, not an array
            $selected_template = Website::get('template.selected');
            if (is_string($selected_template)) {
                $this->template_name = $selected_template;
            } else {
                $this->template_name = Website::get('template.selected.' . static::$template_file, '__NOT_FOUND__');
            }
        }
        if (empty(static::$template_file)) {
            static::$template_file = 'frontend';
        }
        $new_template = 'template/' . $this->template_name . '/' . static::$template_file;
        if (!Kohana::find_file('views', $new_template)) {
            $new_template = 'template/default/' . static::$template_file;
        }
        Website::set_template($this->template_name);

        $current_url = URL::Site(Request::detect_uri(), true);
        View::bind_global('current_url', $current_url);

        $this->template = $new_template;
        parent::before();


    }

}