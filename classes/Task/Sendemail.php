<?php defined('SYSPATH') or die('No direct script access.');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class Task_Sendemail
 */
class Task_Sendemail extends Minion_Task
{
    protected static $settings = array();

    public function get_options()
    {
        static::$settings = json_decode(WEBSITE, true);
        static::$settings['website'] = array_merge_recursive(static::$settings['website'], Kohana::$config->load('website')->as_array());

        return parent::get_options();
    }

    /**
     * Gets a template and sends the email from queue
     *
     * @return null
     */
    protected function _execute(array $params)
    {
        $queue_name = Arr::path(static::$settings, 'website.domain_name') . ':'. Environment::level() . ':' . 'forgot-message';
        $queue_settings = Arr::path(static::$settings, 'rabbitmq');
        print_r($queue_name);echo  "\n\n";

        $connection = new AMQPStreamConnection($queue_settings['host'], $queue_settings['port'], $queue_settings['user'],
            $queue_settings['password']);
        $channel = $connection->channel();
        $channel->queue_declare($queue_name, false, true, false, false);

        $callback = function($msg){
            echo " [x] Received ", $msg->body, "\n";
            $data = json_decode($msg->body, true);

            if (!isset($data['contact_email'])) {
                $data['contact_email'] = Arr::path(static::$settings, 'website.contact_email', 'debug@portnumber53.com');
            }

            $model_setting = new Model_Setting();
            $template_data = $model_setting->getDataByName('email-template-' . $data['template']);

            $search_replace_data = array(
                '__NAME__' => $data['name'],
                '__EMAIL__' => $data['email'],
                '__MESSAGE__' => $data['message'],
            );
            $body = str_replace(array_keys($search_replace_data), array_values($search_replace_data), $template_data['body']);
            $subject = str_replace(array_keys($search_replace_data), array_values($search_replace_data), $template_data['subject']);

            print_r($search_replace_data);
            print_r($data);
            $result = mail($data['contact_email'], $subject, $body);
            var_dump($result);
            ob_flush();
            sleep(5);
            echo " [x] Done", "\n";
            ob_flush();
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };


        $channel->basic_qos(null, 1, null);
        $channel->basic_consume($queue_name, '', false, false, false, false, $callback);

        while(count($channel->callbacks)) {
            $channel->wait();
        }


        $channel->close();
        $connection->close();

    }
}
