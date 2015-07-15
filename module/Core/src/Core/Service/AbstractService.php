<?php
/**
 * @link http://kimprince.com/starter/starter-application-v10 for usage info
 */
namespace Core\Service;

use Core\HelperTrait;
use Core\Domain;
use Core\Exception;
use Zend\ServiceManager;
use Zend\Db\Adapter;
use Zend\EventManager;

/**
 * Abstract Service Class
 */
abstract class AbstractService implements
    ServiceManager\ServiceLocatorAwareInterface,
    Adapter\AdapterAwareInterface,
    EventManager\EventManagerAwareInterface
{
    use HelperTrait;

    /**
     * @var ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var Adapter\Adapter
     */
    public static $db;

    /**
     * @var EventManager\EventManagerInterface
     */
    protected $eventManager;

    /**
     * @var Event\AbstractListener
     */
    protected $listener;

    /**
     * Construct
     *
     * @param Event\AbstractListener
     */
    public function __construct(Event\AbstractListener $listener)
    {
        $this->serviceListener = $listener;
    }

    /**
     * Set service locator
     *
     * @param ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Set database adapter
     *
     * @param Adapter\Adapter $adapter
     */
    public function setDbAdapter(Adapter\Adapter $adapter)
    {
        self::$db = $adapter;
    }

    /**
     * Get database adapter
     *
     * @return Adapter\Adapter
     */
    public function getDbAdapter()
    {
        return self::$db;
    }

    /**
     * Set event manager
     *
     * Sets event manager then injects it into the service listener.
     *
     * @param EventManager\EventManagerInterface $em
     * @return $this|void
     */
    public function setEventManager(EventManager\EventManagerInterface $em)
    {
        $this->eventManager = $em;
        $this->listener->attach($this->eventManager);

        return $this;
    }

    /**
     * Get event manager
     *
     * @return EventManager\EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->eventManager) {
            $this->setEventManager(new EventManager\EventManager());
        }

        return $this->eventManager;
    }

    /**
     * Set listener
     *
     * Override the listener that was provided at construction.
     *
     * @param Event\AbstractListener $listener
     */
    public function setListener(Event\AbstractListener $listener)
    {
        $this->listener = $listener;
    }

    /**
     * Get service listener
     *
     * @return Event\AbstractListener
     */
    public function getServiceListener()
    {
        return $this->listener;
    }
}