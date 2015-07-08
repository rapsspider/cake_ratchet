# Ratchet Plugin for CakePHP 3.X

## Requirements

ZeroMQ : http://www.zeromq.org/

## Installation

@TODO

## Configuration

Add this at the end of your _config\app.php_ file.

    /**
     * Ratchet configuration
     */
    'Ratchet' => [
        'Server' => [
            'host' => '0.0.0.0',
            'port' => 8080
        ],
        'ZMQServer' => [
            'host' => '127.0.0.1',
            'port' => 5555
        ],
    ]

## Start

First, you need to start the server, to do this just run this command
on your cakephp folder : _.\bin\cake ratchet start_

## Examples

    namespace App\Controller;
    use (@TODO)\Pusher;
    
    public class MyController {
    
        public function index() {
            Pusher::send('my_topic','my_message');
        }
        
    }