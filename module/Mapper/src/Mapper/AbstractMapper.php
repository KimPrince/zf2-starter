<?php
/**
 * @link http://kimprince.com/starter/starter-application-v10 for usage info
 */
namespace Mapper;

use Core\HelperTrait;
use Core\Domain\DomainWatcher;
use Core\Domain\Factory\AbstractFactory;
use Zend\Db\Adapter\Adapter;

/**
 * Abstract mapper class
 */
abstract class AbstractMapper
{
    use HelperTrait;

    /**
     * @var AbstractFactory
     */
    protected $factory;

    /**
     * @var DomainWatcher
     */
    protected $watcher;

    /**
     * @var Adapter
     */
    protected $adapter;

    /**
     * Construct
     *
     * @param AbstractFactory $factory
     * @param DomainWatcher $watcher
     * @param Adapter $adapter
     * @return AbstractMapper
     */
    public function __construct(AbstractFactory $factory, DomainWatcher $watcher, Adapter $adapter)
    {
        $this->factory = $factory;
        $this->watcher = $watcher;
        $this->adapter = $adapter;
    }

    /**
     * Get factory
     *
     * @return AbstractFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * Get watcher
     *
     * @return DomainWatcher
     */
    public function getWatcher()
    {
        return $this->watcher;
    }

    /**
     * Get database adapter
     *
     * @return Adapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Create
     *
     * @param $array
     * @return \Core\Domain\AbstractDomain
     */
    public function create(array $array)
    {
        return $this->factory->create($this->convertKeysToCamel($array));
    }

    /**
     * Convert keys to camel case recursively
     *
     * Database field names are lowercase and many include the underscore character. This method
     * transforms array keys (record sets) into camel case format.  If the array includes sub arrays
     * these are also transformed.
     *
     * @param array $data
     * @return array
     */
    public static function convertKeysToCamel(array $data)
    {
        $output = array();
        $func   = create_function('$c', 'return strtoupper($c[1]);');

        foreach ($data as $key => $value) {

            if (is_array($value)) {
                $value = self::convertKeysToCamel($value);
            }

            $newKey = preg_replace_callback('/_([a-z])/', $func, $key);
            $output[$newKey] = $value;
        }

        return $output;
    }
}