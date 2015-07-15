<?php
/**
 * @link http://kimprince.com/starter/starter-application-v10 for usage info
 */
namespace Core\Domain\Collection\Proxy;

use Core\Domain\Collection\iCollection;
use Core\iMapper\Mapper;

/**
 * Abstract collection proxy
 *
 * Stores a mapper, method and arguments, and retrieves a collection on demand.
 */
abstract class AbstractCollectionProxy implements \IteratorAggregate, \Countable
{
    /**
     * Inner iterator
     *
     * @var iCollection\Collection
     */
    protected $iterator;
    
    /**
     * Mapper to load the collection
     *
     * @var Mapper
     */
    protected $mapper;
    
    /**
     * Mapper method to call
     *
     * @var string
     */
    protected $method;
    
    /**
     * Arguments to pass to the mapper method
     *
     * @var array
     */
    protected $arguments;

    /**
     * Construct
     *
     * @param Mapper $mapper
     * @param string $method
     * @param array $arguments
     * @return AbstractCollectionProxy
     */
    public function __construct(Mapper $mapper, $method, array $arguments)
    {
        $this->mapper = $mapper;
        $this->method = $method;
        $this->arguments = $arguments;
    }

    /**
     * @see InteratorAggregator::getIterator()
     * @return \Countable
     */
    public function getIterator() 
    {
        if ($this->iterator === null) {
            $this->iterator = call_user_func_array(
                array($this->mapper, $this->method), $this->arguments);
        }
        
        return $this->iterator;
    }

    /**
     * Count
     *
     * @return integer
     */
    public function count()
    {
        return $this->getIterator()->count();
    }

    /**
     * Route custom method calls to the iterator
     * 
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, array $arguments) 
    {
        return call_user_func_array(
            array($this->getIterator(), $name), $arguments);
    }
}