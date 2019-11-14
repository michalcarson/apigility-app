<?php

namespace DealerInspire\ApigilityApp;

use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        (new App())->bootstrap($e);
    }
}
