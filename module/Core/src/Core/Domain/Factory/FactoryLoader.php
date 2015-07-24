<?php
/**
 * @link http://kimprince.com/starter/starter-application-v10 for usage info
 */
namespace Core\Domain\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;

class FactoryLoader implements AbstractFactoryInterface
{
    /**
     * Can create service with name
     *
     * Check that the service requested was prefixed with 'Core\Domain\Factory', and that
     * the requested mapper class exists.
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $prefix = substr($requestedName, 0, 19);

        if ($prefix == 'Core\Domain\Factory' && class_exists($requestedName)) {
            return true;
        }

        return false;
    }

    /**
     * Create service with name
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $watcher = $serviceLocator->get('Core\Watcher');
        return new $requestedName($watcher);
    }
}