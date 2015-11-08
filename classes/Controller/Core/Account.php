<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 11/8/2015
 * Time: 12:17 AM
 */

class Controller_Core_Account extends Controller_Website
{
    public $auth_required = false;

    public function action_login()
    {
        $main = 'account/login';

        View::bind_global('main', $main);

    }

    public function action_profile()
    {
        $main = 'account/profile';

        View::bind_global('main', $main);

    }

    public function action_signup()
    {
        $main = 'account/signup';

        View::bind_global('main', $main);

    }

}