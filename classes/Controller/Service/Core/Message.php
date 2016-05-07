<?php defined('SYSPATH') or die('No direct script access.');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class Controller_Service_Core_Message
 */
class Controller_Service_Core_Message extends Controller_Website
{
    public $auto_render = false;

    public function action_ajax_send()
    {
        $this->output['_DEBUG'] = $_POST;

        $email_data = array(
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'message' => $_POST['message'],
            'template' => 'general-message',
        );


        $queue_name = Arr::path(self::$settings, 'website.domain_name') . ':'. Environment::level() . ':' . 'forgot-message';
        $queue_settings = Arr::path(self::$settings, 'rabbitmq');

        $connection = new AMQPStreamConnection($queue_settings['host'], $queue_settings['port'],
            $queue_settings['user'], $queue_settings['password']);
        $channel = $connection->channel();
        $channel->queue_declare($queue_name, false, true, false, false);


        $msg = new AMQPMessage(json_encode($email_data), array('delivery_mode' => 2));

        $result = $channel->basic_publish($msg, '', $queue_name);

        $channel->close();
        $connection->close();

        $this->output['result'] = $result;

    }
}
