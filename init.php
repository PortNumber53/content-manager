<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 11/7/2015
 * Time: 8:01 PM
 */

if (!Route::$cache) {
    Route::set('backend-actions', 'backend(/<controller>(/<action>(/<request>)))', array(
        'request' => '[a-zA-Z0-9_/\-]+',
    ))->defaults(array(
        'directory' => 'Backend',
        'controller' => 'Dashboard',
        'action' => 'Main',
    ));

    Route::set('service-actions', 'service(/<controller>(/<action>(/<request>)))', array(
        'request' => '[a-zA-Z0-9_/\-]+',
    ))->defaults(array(
        'directory' => 'Service',
        'controller' => 'Check',
        'action' => 'Unique',
    ));

    Route::set('account-actions', '<action>(/<request>)', array(
        'request' => '[a-zA-Z0-9_/\-]+',
        'action' => '(profile|login|signup|logout|reset|forgot|settings|public)',
    ))->defaults(array(
        'controller' => 'Account',
        'action' => 'profile',
    ));



    Route::set('html-content', '(<request>(<override>))', array(
        'request' => '[a-zA-Z0-9_/\-\.]+\.html',
        'override' => '(:edit)',
    ))->filter(function ($route, $params, $request) {
        // Prefix the method to the action name
        if (!empty($params['override']) && $params['override'] == ':edit') {
            $params['action'] = 'edit';
            $params['directory'] = 'Backend';
        }
        //$params['action'] = strtolower($request->method()).'_'.$params['action'];
        return $params; // Returning an array will replace the parameters
    })->defaults(array(
        'controller' => 'Content',
        'action' => 'view',
        'request' => '/',
    ));

    Route::set('image-actions', '<request>.<type>', array(
        'request' => '[a-zA-Z0-9_/\-]+',
        'type' => '(jpg|jpeg|png|gif)',
    ))->defaults(array(
        'controller' => 'Dynimage',
        'type' => 'jpg',
        'action' => 'get',
    ));

    Route::set('sitemap', 'sitemap/<name>(:<page>).<format>', array(
        'name' => '[a-zA-Z0-9_/\-]+',
        'page' => '([0-9]+|count)',
        'format' => '(xml|txt)',
    ))->defaults(array(
        'controller' => 'Sitemap',
        'action' => 'generate',
    ));

    Route::set('seo-robots', 'robots.txt')->defaults(array(
        'controller' => 'Robots',
        'action' => 'index',
    ));

}
