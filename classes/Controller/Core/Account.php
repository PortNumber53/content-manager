<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Controller_Core_Account
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

    public function action_ajax_signup()
    {
        $data = Account::factory()->profile();

        $this->output = array(
            'posted' => $_POST,
        );
        $error = false;

        $signup_data = array(
            //'accountid'=> Arr::path($data, 'accountid', 0),
            'profile' => 'user',
            'username' => filter_var($_POST['username'], FILTER_SANITIZE_EMAIL),
            'password' => filter_var($_POST['password'], FILTER_SANITIZE_STRING),
            'display_name' => filter_var($_POST['display_name'], FILTER_SANITIZE_STRING),
        );
        $result = Account::signup($signup_data, $error);

        if ($error === false) {
            $this->output['redirectUrl'] = URL::Site(Route::get('account-actions')->uri(array('action' => 'profile',)),
                true);
        }

        $this->output['error'] = $error;
        $this->output['output'] = $result;
    }

}
