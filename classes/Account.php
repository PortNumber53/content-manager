<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 11/8/2015
 * Time: 12:40 PM
 */

/**
 * Class Account
 */
class Account extends Core_Account
{

    public static function profile($_id = '', $options = array())
    {
        $cookie = json_decode(Cookie::get('account'), true);
        $accountData = Model_Account::getAccountByUsername($cookie['username']);

        if (empty($options[self::REMOVE_SENSITIVE])) {
            $options[self::REMOVE_SENSITIVE] = true;
        }

        if (!empty($options)) {
            if (in_array(self::REMOVE_SENSITIVE, $options)) {
                unset($accountData['password'], $accountData['hash']);
            }
        }

        return $accountData;
    }

}
