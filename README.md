# apigility-app
Static access to the Application and ServiceManager objects from within Apigility

This module allows a developer to get an instance of the Application object
from anywhere in the application. Through the Application object, developers have
access to the ServiceManager, ModuleManager, EventManager and other objects
that were previously difficult to access from within a standard class.

Having access to the ServiceManager makes it possible to use the pre-defined factories 
to create "new" instances of classes and thus to avoid the PHP `new` operation. This
solves several problems with testing. The test can create a mock, set this in the ServiceManager
ahead of time, and this mock will be given to the requesting class (presumably, the subject
under test) in place of the actual class.

This module should not be used in place of good dependency injection designs.
We have developed it as a remedy for legacy code as we refactor it step-by-step.
If development schedules allow, we expect to use this code only in a transition
phase. But this is the real world.

## Installation

This package is set up for installation via Composer.

```shell script
php composer.phar require dealerinspire/apigility-app
```

Then you must add this module to your `config/application.config.php`
file. Within the `modules` array, add 'DealerInspire\\\\ApigilityApp'.

```php
return [
    'modules' => [
        ...
        'DealerInspire\\ApigilityApp'
    ],
]
```

## Usage

If properly initialized, retrieving the Application object or the ServiceManager
object is a static call.

```php
$application = App::getApplication();
$serviceManager = App::getServiceManager();
```

For convenience, there is a `get` method that will return any class or alias
known to the ServiceManager.

```php
$config = App::get('config');
$myClass = App::get(MyClass::class);
```

Classes you want to retrieve in this way must be properly set up with factories.
Simple classes with no dependencies can be defined as "invokables". As an
example, your `module.config.php` might contain lines like the following.

```php
return [
    'service_manager' => [
        'factories' => [
            MyClass::class => function(ContainerInterface $container, $requestedName) {
                return new MyClass(
                    $container->get(SimpleClass::class)
                );
            }
        ],
        'invokables' => [
            SimpleClass::class => SimpleClass::class,
        ],
    ],
];
```

For other ServiceManager options, refer to 
[Configuring the Service Manager](https://docs.zendframework.com/zend-servicemanager/configuring-the-service-manager/).

## Modifications to Unit Tests

You may find that the App class is not properly initialized in your unit test
environment. Typically, this is evidenced by an exception with a message
"call to getServiceManager on null". This indicates that the modules have
not been loaded or that the Application object has not been initialized (it
must call the 'bootstrap' event).

Somewhere within your TestCase, you need to include code similar to
the following.

```php
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;
use ZF\Apigility\Application;

$config = file_get_contents('../config/application.config.php');

$serviceManager = new ServiceManager(new ServiceManagerConfig());
$serviceManager->setService('ApplicationConfig', $config);
$serviceManager->get('ModuleManager')->loadModules();
$application = new Application($config, $serviceManager);
$application->bootstrap();
```

You will almost certainly need to modify the paths within your `$config` 
variable so they are referenced from your test directory. Expect other
changes, too. This subject is beyond our scope here but you can make it 
work! I believe in you!


