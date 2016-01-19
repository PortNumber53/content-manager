<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Controller_Core_Website
 */
class Controller_Core_Website extends Controller_Template
{
    public $template_name = '';
    public static $template_file = 'frontend';

    public static $settings = array();

    public $auth_required = false;
    public $auth_actions = array();

    protected static $template_modules = array();
    protected static $account = null;


    public function __construct(Request $request, Response $response)
    {
        $dotSettings = defined(WEBSITE) ? array() : json_decode(WEBSITE, true);

        $settings = Kohana::$config->load('website')->as_array();
        self::$settings = array_merge($settings, $dotSettings);
        View::set_global('debug', Arr::path(self::$settings, 'debug', false));

        //$this->frontend_cookie = json_decode(Cookie::get(Constants::FE_COOKIE), true);
        //$this->backend_cookie = json_decode(Cookie::get(Constants::BE_COOKIE), true);

        parent::__construct($request, $response);

        //If a user is not logged in and authentication is required:
        if ($this->auth_required && !Auth::instance()->logged_in()) {
            $this->redirect('/login?url=' . URL::site(Request::current()->uri()));
        }

        if (in_array($this->request->action(), $this->auth_actions)) {
            if (!Account::factory()->isLoggedIn()) {
                echo $this->request->action() . ' requires Authentication!';
                $this->redirect('/login?url=' . URL::site(Request::current()->uri()));
            }
            //$this->template_file = 'backend';
        }

        if (strpos(strtolower($this->request->headers('accept')),
                'application/json') !== false || $this->request->is_ajax() || !empty($this->json) || (strtolower($this->request->controller()) == 'upload' && strtolower($this->request->action()) == 'receive')
        ) {
            $this->json = json_decode(file_get_contents('php://input'), true);
            $this->auto_render = false;
            $this->request->action('ajax_' . $this->request->action());
            $this->response->headers('content-type', 'application/json');
        }

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
        View::bind_global('site_settings', self::$settings);

        $this->template = $new_template;

        if (Account::factory()->isLoggedIn() && !Account::factory()->isGuestUser() && (static::$account = Account::factory()->profile())) {
        } else {
            static::$account = Account::factory()->createGuest();
        }
        View::bind_global('account', static::$account);

        if ($this->auto_render) {
            View::bind_global('template_modules', static::$template_modules);
        }
        parent::before();
    }

    public function after()
    {
        if ($this->auto_render) {

        } else {
            $content_type = Arr::path($this->response->headers(), 'content-type', 'text/html');
            switch ($content_type) {
                case 'application/json':
                    $this->response->body(json_encode($this->output));
                    break;
                default:
                    $this->response->body($this->output);
            }
        }
        parent::after();
    }
}
