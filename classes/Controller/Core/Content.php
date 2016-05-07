<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class Controller_Core_Content
 */
class Controller_Core_Content extends Controller_Website
{
    public function action_view()
    {
        $request = Request::current()->param('request', '/');
        $type = Request::current()->param('type', 'html');

        $content_model = new Model_Content();
        $content_data = $content_model->getById('/'.$request);

        $most_visited = Model_Content::getMostVisited();
        $random_content = Model_Content::getRandom();

        View::bind_global('most_visited', $most_visited);
        View::bind_global('random_content', $random_content);

        if ($content_data) {

            /*
            $message_data = array(
                '_id' => $content_data['_id'],
            );
            $queue_name = Environment::level() . '-' . 'truvisco-view-content';
            $queue_settings = Arr::path(self::$settings, 'rabbitmq');
            $connection = new AMQPStreamConnection($queue_settings['host'], $queue_settings['port'],
                $queue_settings['user'], $queue_settings['password']);
            $channel = $connection->channel();
            $channel->queue_declare($queue_name, false, true, false, false);
            $msg = new AMQPMessage(json_encode($message_data), array('delivery_mode' => 2));
            $result = $channel->basic_publish($msg, '', $queue_name);

            $channel->close();
            $connection->close();
            */

            View::bind_global('content_data', $content_data);
            $main = 'content/post';
            Model_Content::incrementViewCount('/'.$request);
        } else {
            $main = 'content/frontpage';
        }

        View::bind_global('main', $main);
    }
}
