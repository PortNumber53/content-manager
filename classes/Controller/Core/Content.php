<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 11/7/2015
 * Time: 8:06 PM
 */

/**
 * Class Controller_Core_Content
 */
class Controller_Core_Content extends Controller_Website
{
    public function action_view()
    {
        $main = 'content/frontpage';

        View::bind_global('main', $main);
    }
}
