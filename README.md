# Ratchet Plugin for CakePHP 3.X

## Requirements

ZeroMQ : http://www.zeromq.org/

## Installation

    composer require rapsspider/cake_ratchet

## Configuration

Add this at the end of your _config\app.php_ file:

    /**
     * Ratchet configuration
     */
    'CakeRatchet' => [
        'Server' => [
            'host' => '0.0.0.0',
            'port' => 8080
        ],
        'ZMQServer' => [
            'host' => '127.0.0.1',
            'port' => 5555
        ],
        'JSHelper' => true
    ]
    
Add this in your _config\bootstrap.php_ file:

    Plugin::load('CakeRatchet', ['bootstrap' => true]);
    
It's possible you need to add this on your _vendors/cakephp-plugins.php_ file:
    
    ...
    'plugins' => [
        ...
        'CakeRatchet' => $baseDir . '/vendor/rapsspider/cake_ratchet/',
        ...
    ]

## Start

First, you need to start the server, to do this just run this command
on your cakephp folder : _.\bin\cake ratchet start_

## Examples

### Server

    namespace App\Controller;
    use (@TODO)\Pusher;
    
    public class MyController {
    
        public function index() {
            Pusher::send('my_topic','my_message');
        }
        
    }
    
### Client

If JSHelper is activate, an fonction will be available:

    <script>
    var onConnect = function(connection) {
        connection.subscribe('my_topic', function(topic, data) {
            // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
            console.log('New article published to category "' + topic + '" : ');
            console.log(data);
        });
    };
    
    var onClose = function(connection) {
    
    };
    
    CakeRatchet.connection(onConnect, onClose); 
    </script>

Else, you can do this :

    <script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
	<script>
		var conn = new ab.Session('ws://localhost:8080',
			function() {
				conn.subscribe('my_topic', function(topic, data) {
					// This is where you would add the new article to the DOM (beyond the scope of this tutorial)
					console.log('New article published to category "' + topic + '" : ');
					console.log(data);
				});
				console.log('Connexion réussie');
			},
			function() {
				console.warn('WebSocket connection closed');
			},
			{'skipSubprotocolCheck': true}
		);
	</script>