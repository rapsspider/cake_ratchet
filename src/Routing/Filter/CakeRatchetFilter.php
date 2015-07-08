<?php

namespace CakeRatchet\Routing\Filter;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Event\EventManagerTrait;
use Cake\ORM\TableRegistry;
use Cake\Routing\DispatcherFilter;
use Cake\Routing\Router;
use DebugKit\Panel\DebugPanel;
use DebugKit\Panel\PanelRegistry;

/**
 * JavaScript injector filter.
 *
 */
class CakeRatchetFilter extends DispatcherFilter
{
    
    use EventManagerTrait;
    
    /**
     * Constructor
     *
     * @param \Cake\Event\EventManager $events The event manager to use.
     * @param array $config The configuration data for CakeRatcher.
     */
    public function __construct(EventManager $events)
    {
        $this->eventManager($events);
    }
    
    /**
     * Event bindings
     *
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'Dispatcher.afterDispatch' => [
                'callable' => 'afterDispatch',
                'priority' => 9999,
            ],
        ];
    }
    
    /**
     * Save the JavaScript class Data.
     *
     * @param \Cake\Event\Event $event The afterDispatch event.
     * @param \Cake\Network\Response $response The response to augment.
     * @return void
     */
    public function afterDispatch(Event $event)
    {
        $request = $event->data['request'];
        // Skip debugkit requests and requestAction()
        if ($request->param('plugin') === 'DebugKit' || $request->is('requested')) {
            return;
        }
        
        if(Configure::read('CakeRatchet.JSHelper')) {
            $this->_injectScripts($event->data['response']);
        }
    }
    
    /**
     * Inject the JS to build the CakeRatchet Javascript Class
     * @return void
     */
    protected function _injectScripts($response)
    {
        if (strpos($response->type(), 'html') === false) {
            return;
        }
        $body = $response->body();
        $pos = strrpos($body, '<script');
        if ($pos === false && ($pos = strrpos($body, '</body>')) === false) {
            return;
        }
        $url = Router::url('/', true);
        $script = '<script>CakeRatchet = {"host":location.host, "port":"' . Configure::read('CakeRatchet.Server.port') . '"}</script>'
                 .'<script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>'
                 .'<script src="' . Router::url('/cake_ratchet/js/cake_ratchet.js') . '"></script>';
        $body = substr($body, 0, $pos) . $script . substr($body, $pos);
        $response->body($body);
    }
}