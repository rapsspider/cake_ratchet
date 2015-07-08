<?php
namespace App\ZMQ;

use Cake\Core\Configure;
use ZMQ;
use ZMQContext;

/**
 * Class that help you to send to you ZMQ server.
 */
class Pusher {
    
    /**
     * Send data to the ZMQ server.
     * @param String $topic Name of the topic/category to associate this
     *                      message.
     * @param Array  $data  Data to send
     */
    public static function send($topic, $data) {
        self::sendAction($topic, $data, null);
    }
    
    /**
     * Send data using a specific structure to inform
     * the client that the data passed has been added.
     *
     * @param String $topic Name of the topic/category to associate this
     *                      message.
     * @param Array  $data  Data to send
     */
    public static function sendAdd($topic, $data) {
        self::sendAction($topic, $data, 'add');
    }
    
    /**
     * Send data using a specific structure to inform
     * the client that the data passed has been deleted.
     *
     * @param String $topic Name of the topic/category to associate this
     *                      message.
     * @param Array  $data  Data to send
     */
    public static function sendDeleted($topic, $data) {
        self::sendAction($topic, $data, 'delete');
    }
    
    /**
     * Send data using a specific structure to inform
     * the client that the data passed has been updated.
     *
     * @param String $topic Name of the topic/category to associate this
     *                      message.
     * @param Array  $data  Data to send
     */
    public static function sendUpdated($topic, $data) {
        self::sendAction($topic, $data, 'update');
    }
    
    /**
     * Send data using a specific structure to inform
     * the client about an action done on the data.
     *
     * @param String $topic  Name of the topic/category to associate this
     *                       message.
     * @param Array  $data   Data to send
     * @param String $action Action to use.
     */
    public static function sendAction($topic, $data, $action) {
        $context = new ZMQContext();
        $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://" . Configure::read('Ratchet.ZMQServer.host') . ":" . Configure::read('Ratchet.ZMQServer.port'));
        
        $socket->send(json_encode([
            'topic' => $topic, 
            'data' => [
                'action' => $action,
                'data' => $data
            ]
        ]));
    }
}