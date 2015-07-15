<?php
/**
 * @link http://kimprince.com/starter/starter-application-v10 for usage info
 */
namespace Core\Domain;

use Core;

/**
 * Domain watcher class
 *
 * Implements the identity map pattern so that only one version of a domain
 * entity is ever active.
 */
class DomainWatcher
{
    use Core\HelperTrait;

    /**
     * @var DomainWatcher
     */
    static private $instance = null;

    /**
     * Currently active objects
     *
     * @var array
     */
    private $all = array();

    /**
     * Singleton constructor
     */
    private function __construct() { }

    /**
     * Prevent clones
     */
    private function __clone() { }

    /**
     * Get instance
     *
     * @return DomainWatcher
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Get global key
     *
     * The globally unique key is a combination of the object's short-type
     * and its identifier.
     *
     * @param AbstractDomain $object
     * @return string
     */
    public function globalKey(AbstractDomain $object)
    {
        return $this->getShortType($object) . '.' . $object->getId();
    }
        
    /**
     * Add an object to the identity map
     *
     * @param AbstractDomain $object
     */
    public function add(AbstractDomain $object)
    {
        $this->all[$this->globalKey($object)] = $object;
    }

    /**
     * Exists
     *
     * If an object exists in the map, return it
     * 
     * @param string $shortType
     * @param string|int $id
     * @return AbstractDomain|null
     */
    public function exists($shortType, $id)
    {
        $key = "$shortType.$id";
        
        if(isset($this->all[$key])) {
            return $this->all[$key];
        }

        return null;
    }

    /**
     * Reset
     *
     * Empty the watcher
     */
    public function reset()
    {
        $this->all = array();
    }
}