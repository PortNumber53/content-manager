<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 11/8/2015
 * Time: 12:55 PM
 */


return array(
    'redis' => array(
        'driver'             => 'redis',
        'default_expire'     => 3600,
        'compression'        => FALSE,              // Use Zlib compression (can cause issues with integers)
        'servers'            => array(
            'local' => array(
                'host'             => 'localhost',  // Memcache Server
                'port'             => 6379,         // Memcache port number
                'persistent'       => FALSE,        // Persistent connection
                'weight'           => 1,
                'timeout'          => 1,
                'retry_interval'   => 15,
                'status'           => TRUE,
            ),
        ),
        'instant_death'      => TRUE,               // Take server offline immediately on first fail (no retry)
    ),
);
