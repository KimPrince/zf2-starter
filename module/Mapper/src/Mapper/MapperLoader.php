<?php
/**
 * @link http://kimprince.com/starter/starter-application-v10 for usage info
 */
namespace Mapper\Mapper;

use Core\HelperTrait;
use Core\Domain\DomainWatcher;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;

/**
 * Mapper loader class
 *
 * This abstract factory matches calls for services named 'Mapper\SomeName' and
 * instantiates the requested mapper.
 */
class MapperLoader implements AbstractFactoryInterface
{
    use HelperTrait;

    /**
     * Can create service with name
     *
     * Check that the service requested was prefixed with 'Mapper\Mapper', and that
     * the requested mapper class exists.
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $prefix = substr($requestedName, 0, 13);

        if ($prefix == 'Mapper\Mapper' && class_exists($requestedName)) {
            return true;
        }

        return false;
    }

    /**
     * Create service with name
     *
     * Check that the service requested was prefixed with 'Mapper'.  Check that the class exists,
     * then instantiate it with a db adapter injected
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $shortName = $this->getShortType($requestedName);
        $db = $serviceLocator->get('Zend\Db\Adapter\Adapter');

        $watcher = DomainWatcher::getInstance();
        $factory = $serviceLocator->get("Core\\Domain\\Factory\\$shortName");

        return new $requestedName($factory, $watcher, $db);
    }
}