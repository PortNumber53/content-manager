<?php defined('SYSPATH') or die('No direct script access.');

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
