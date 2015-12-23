<?php defined('SYSPATH') or die('No direct script access.');

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

    public static function signup($signup_data = '', $errors = null)
    {
        if (!is_array($errors)) {
            $errors = array();
        }
        if (empty($signup_data['username'])) {
            $errors[] = array(
                'username' => 'Username cannot be empty.',
            );
        }
        if (empty($signup_data['password'])) {
            $errors[] = array(
                'password' => 'Password cannot be empty.',
            );
        }

        if (count($errors) === 0) {
            $model_account = new Model_Account();
            $result = $model_account->save($signup_data, $errors);
        } else {
            return false;
        }

    }

}
