<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 11/8/2015
 * Time: 12:41 PM
 */

/**
 * Class Core_Account
 */
class Core_Account extends Abstracted
{

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
        // Check if we already have the guest cookie
        //$cookie = json_decode(Cookie::get('account'), true);
        //if (isset($cookie['profile'])) {
        //    return true;
        //}

        $error = false;
        $username = 'guest_' . str_replace('.', '', microtime(true) . mt_rand(10000, 99999));
        $data = array(
            '_id' => '/' . DOMAINNAME . '/' . $username,
            'profile' => 'guest',
            'username' => $username,
            'password' => '123',
            'display_name' => $username,
            'name' => $username,
        );
        $result = Model_Account::saveRow($data, $error);
        //Force a login
        if ($result) {
            //Only store minimal information in the cookie
            $data_cookie = array(
                '_id' => $result['_id'],
                'display_name' => $data['display_name'],
                'username' => $data['username'],
                'profile' => 'guest',
            );
            Cookie::set('account', json_encode($data_cookie));
        }
        return $data_cookie;
    }

}
