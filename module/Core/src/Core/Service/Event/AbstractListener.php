<?php
/**
 * @link http://kimprince.com/starter/starter-application-v10 for usage info
 */
namespace Core\Service\Event;

/**
 * Class Abstract Listener
 */
abstract class AbstractListener
{
    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $sm
     */
    public function __construct(\Zend\ServiceManager\ServiceLocatorInterface $sm)
    {
        $this->serviceLocator = $sm;
    }

    /**
     * Attach listeners to event manager
     *
     * @param \Zend\EventManager\EventManagerInterface $em
     */
    abstract public function attach(\Zend\EventManager\EventManagerInterface $em);
}