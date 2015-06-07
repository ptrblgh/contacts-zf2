<?php

namespace Application\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

/**
 * Set Utf-8 charset for response on dispatch event
 * 
 * @author Péter Balogh <peter.balogh@theory9.hu>
 */
class Utf8ResponseListener implements ListenerAggregateInterface
{
    /**
     * @var \Zend\StdLib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * {@inhertiDoc}
     * 
     * @param  EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_DISPATCH, 
            array($this, 'onDispatch')
        );
    }
    
    /**
     * {@inhertiDoc}
     * 
     * @param  EventManagerInterface $events
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * Sets utf-8 characterset header for response object
     * 
     * @param  MvcEvent $event
     * @return void
     */    
    public function onDispatch(MvcEvent $event)
    {
        $response = $event->getResponse();
        if ($response instanceof \Zend\Http\Response) {
            $headers = $response->getHeaders();
            if ($headers) {
                $headers->addHeaderLine(
                    'Content-Type', 
                    'text/html; charset=UTF-8'
                );
            }
        }
    }
}