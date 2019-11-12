<?php

namespace DealerInspire\Apigility\Tests;

use DealerInspire\ApigilityApp\App;
use PHPUnit\Framework\TestCase;
use Zend\Mvc\Application;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\ArrayUtils;

class AppTest extends TestCase
{
    public function setUp()
    {
        $testConfig = include('./test/test.config.php');
        $appConfig = include('./config/module.config.php');
        $appConfig = ArrayUtils::merge($appConfig, $testConfig);
        Application::init($appConfig);

        parent::setUp();
    }

    public function testGetApplication()
    {
        $this->assertInstanceOf(Application::class, App::getApplication());
    }

    public function testGetServiceManager()
    {
        $this->assertInstanceOf(ServiceManager::class, App::getServiceManager());
    }

    public function testGet()
    {
        $classname = bin2hex(random_bytes(8));
        App::getServiceManager()->setFactory($classname, function () {
            return new \stdClass();
        });

        $this->assertInstanceOf(\stdClass::class, App::get($classname));
    }
}
