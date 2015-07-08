<?php

use Cake\Core\Configure;
use Cake\Event\EventManager;
use Cake\Routing\DispatcherFactory;
use Cake\Routing\Router;
use CakeRatchet\Routing\Filter\CakeRatchetFilter;

$debugBar = new CakeRatchetFilter(EventManager::instance());

DispatcherFactory::add($debugBar);