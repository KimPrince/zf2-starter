<?php
/**
 * @link http://kimprince.com/starter/starter-application-v10 for usage info
 */
namespace Core\Domain\Factory;

use Core\Domain;
use Core\Exception;
use Core\HelperTrait;
use Zend\ServiceManager;

/**
 * Factory superclass
 */
abstract class AbstractFactory implements ServiceManager\ServiceLocatorAwareInterface
{
    use HelperTrait;

    /**
     * @var Domain\DomainWatcher
     */
    protected $watcher;

    /**
     * @var integer
     */
    protected $flavour = 0;

    /**
     * @var ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * Constructor
     *
     * @param Domain\DomainWatcher $watcher
     */
    public function __construct(Domain\DomainWatcher $watcher)
    {
        $this->watcher = $watcher;
    }

    // factory methods

    /**
     * Create object
     *
     * @param array $data
     * @throws Exception\Factory
     * @return Domain\AbstractDomain
     */
    public function create(array $data = array())
    {
        try {

            if (empty($data['id'])) {
                $this->doNewObjectDefaults($data);
            }

            $this->doTypeConversion($data);

            if (!empty($data['id'])) {
                $this->doAddRelations($data);
            }

            $object = $this->doInstantiation($data);
            $this->doPostInit($object);
            $object = $this->doDomainWatcher($object);

            return $object;

        } catch (\Exception $e) {
            throw new Exception\Factory(
                'Unable to create new ' . $this->getShortType($this), null, $e);
        }
    }

    /**
     * Add new domain object defaults
     *
     * @param array $data
     */
    protected function doNewObjectDefaults(array &$data)
    { }

    /**
     * Do type conversion
     *
     * @param array $data
     */
    protected function doTypeConversion(array &$data)
    { }

    /**
     * Add relations to existing objects
     *
     * @param array $data
     */
    protected function doAddRelations(array &$data)
    { }

    /**
     * Do instantiation
     *
     * @param array $data
     * @return Domain\AbstractDomain
     */
    abstract protected function doInstantiation(array $data);

    /**
     * Do post init
     *
     * @param Domain\AbstractDomain $object
     */
    protected function doPostInit(Domain\AbstractDomain $object)
    { }

    /**
     * Do domain watcher
     *
     * If an existing entity is already stored in the domain watcher, return this entity in
     * preference to the newly created one.
     *
     * @param Domain\AbstractDomain $object
     * @return Domain\AbstractDomain
     */
    protected final function doDomainWatcher(Domain\AbstractDomain $object)
    {
        if($exists = $this->watcher->exists($this->getShortType($object), $object->getId())) {
            return $exists;
        }

        $this->watcher->add($object);
        return $object;
    }

    // service locator

    /**
     * @param ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    // helpers

    /**
     * Get empty collection
     *
     * @param string $shortName
     * @return Domain\Collection\AbstractCollection
     */
    public function getEmptyCollection($shortName)
    {
        $shortName      = ucfirst($shortName);
        $collectionName = "\\Core\\Domain\\Collection\\$shortName";
        $factorySvcName = "Core\\Domain\\Factory\\$shortName";

        return new $collectionName(($this->serviceLocator->get($factorySvcName)), []);
    }

    /**
     * Get proxy
     *
     * @param string $shortName
     * @param string|integer $identity
     * @return Domain\Proxy\AbstractProxy
     */
    public function getProxy($shortName, $identity)
    {
        $shortName     = ucfirst($shortName);
        $proxyName     = "\\Core\\Domain\\Proxy\\$shortName";
        $mapperSvcName = "Mapper\\$shortName";

        return new $proxyName($identity, $this->serviceLocator->get($mapperSvcName));
    }

    /**
     * Get collection proxy
     *
     * @param string $shortName
     * @param string $methodName
     * @param array $params
     * @return Domain\Collection\Proxy\AbstractCollectionProxy
     */
    public function getCollectionProxy($shortName, $methodName, array $params)
    {
        $shortName      = ucfirst($shortName);
        $proxyName      = "\\Core\\Domain\\Collection\\Proxy\\$shortName";
        $mapperSvcName  = "Mapper\\$shortName";

        return new $proxyName($this->serviceLocator->get($mapperSvcName), $methodName, $params);
    }

    // flavour methods for N+1 selects handling

    /**
     * Set flavour
     *
     * Set flavour for factory, overwriting any existing flavour setting
     *
     * @param integer $flavour
     * @throws Exception\Factory
     */
    public function setFlavour($flavour)
    {
        if (!filter_var($flavour, FILTER_VALIDATE_INT)) {
            throw new Exception\Factory('Invalid filter flavour');
        }

        $this->flavour = $flavour;
    }

    /**
     * Add flavour
     *
     * Add flavour to existing flavour
     */
    public function addFlavour($flavour)
    {
        $this->flavour = $this->$flavour | $flavour;
    }

    /**
     * Get flavour
     *
     * @return integer
     */
    public function getFlavour()
    {
        return $this->flavour;
    }
}