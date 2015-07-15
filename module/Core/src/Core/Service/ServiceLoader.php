<?php
/**
 * @link http://kimprince.com/starter/starter-application-v10 for usage info
 */
namespace Core\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;

class ServiceLoader implements AbstractFactoryInterface
{
    /**
     * Can create service with name
     *
     * Check that the service requested was prefixed with 'Core\Service', and that
     * the requested service class exists.
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $prefix = substr($requestedName, 0, 12);

        if ($prefix == 'Core\Service' && class_exists($requestedName)) {
            return true;
        }

        return false;
    }

    /**
     * Create service with name
     */
    public function createServiceWithName(ServiceLocatorInterface $sm, $name, $requestedName)
    {
        $listenerShortName  = substr($requestedName, 13);
        $listenerName       = "Core\\Service\\Event\\{$listenerShortName}Service";
        $listener           = new $listenerName($sm);

        return new $requestedName($listener);
    }
}