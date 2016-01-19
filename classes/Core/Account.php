<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Core_Account
 */
class Core_Account extends Abstracted
{
    const REMOVE_SENSITIVE = '.';

    protected static $data = array();

    public static function isLoggedIn()
    {
        $cookie_data = Cookie::get('account');
        $array_data = json_decode($cookie_data, true);
        return !empty($cookie_data) && ($array_data['profile'] !== 'guest');
    }

    public static function isGuestUser()
    {
        $cookie_data = Cookie::get('account');
        $array_data = json_decode($cookie_data, true);
        return !empty($cookie_data) && $array_data['profile'] === 'guest';
    }

    public static function createGuest()
    {
        $username = 'guest_' . str_replace('.', '', microtime(true) . mt_rand(10000, 99999));
        $data_cookie = array(
            '_id' => '/' . DOMAINNAME . '/' . $username,
            'id' => 0,
            'profile' => 'guest',
            'username' => $username,
            'display_name' => $username,
            'name' => $username,
        );

        Cookie::set('account', json_encode($data_cookie));
        return $data_cookie;
    }
}
