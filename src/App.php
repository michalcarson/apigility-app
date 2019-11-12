<?php

namespace DealerInspire\ApigilityApp;

use Zend\EventManager\Event;
use Zend\EventManager\EventManager;
use Zend\Mvc\Application as MvcApplication;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\ServiceManager;
use ZF\Apigility\Application as ApigilityApplication;

class App
{
    /** @var MvcApplication|ApigilityApplication */
    static $application;

    /**
     * This method is called on each `listener` in the config. We will register
     * for the `bootstrap` event because it has the objects we need.
     *
     * @param EventManager $events
     */
    public function attach(EventManager $events)
    {
        $events->attach(MvcEvent::EVENT_BOOTSTRAP, [$this, 'bootstrap']);
    }

    /**
     * We will receive the `bootstrap` event here. We will save the application
     * from the event payload, assigning it to a static class variable.
     *
     * @param Event $event
     */
    public function bootstrap(Event $event)
    {
        self::$application = $event->getParam('application');
    }

    /**
     * Return the instantiated Application object.
     *
     * @return MvcApplication|ApigilityApplication
     */
    public static function getApplication()
    {
        return self::$application;
    }

    /**
     * For convenience, return the ServiceManager from the Application.
     *
     * @return ServiceManager
     */
    public static function getServiceManager()
    {
        return self::$application->getServiceManager();
    }

    /**
     * Return any class or alias registered with the ServiceManager.
     *
     * @param string $classname
     * @return array|object
     * @throws ServiceNotFoundException
     */
    public static function get($classname)
    {
        return self::$application->getServiceManager()->get($classname);
    }
}
