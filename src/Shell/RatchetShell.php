<?php

namespace CakeRatchet\Shell;

use CakeRatchet\Wamp\CakeWampAppServer;
    
use Cake\Console\Shell;
use Cake\Core\Configure;

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\Session\SessionProvider;
use Ratchet\Wamp\WampServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\Server;
use React\ZMQ\Context;
use ZMQ;

class RatchetShell extends Shell {
   
    /**
     * @var \React\EventLoop\LoopInterface
     */
    private $__loop;
    
    /**
     * The IO server handling the incoming websocket connections
     *
     * @var \Ratchet\Server\IoServer
     */
    private $__ioServer;

    /**
     * Start the websocket server
     */
    public function start() {
        $this->__loop = Factory::create();
        
        $pusher = new CakeWampAppServer;
        
        $context = new Context($this->__loop);
        $pull = $context->getSocket(ZMQ::SOCKET_PULL);
        $this->out("tcp://" . Configure::read('Ratchet.ZMQServer.host') . ":" . Configure::read('Ratchet.ZMQServer.port'));
        $pull->bind("tcp://" . Configure::read('Ratchet.ZMQServer.host') . ":" . Configure::read('Ratchet.ZMQServer.port'));
        $pull->on('message', array($pusher, 'onBlogEntry'));
        
        $webSock = new Server($this->__loop);
        $webSock->listen(Configure::read('Ratchet.Server.port'), Configure::read('Ratchet.Server.host'));
        
        $this->__ioServer = new IoServer(
            new HttpServer(
                new WsServer(
                    new WampServer(
                        $pusher
                    )
                )
            ),
            $webSock
        );
        
        $this->__loop->run();
    }

}