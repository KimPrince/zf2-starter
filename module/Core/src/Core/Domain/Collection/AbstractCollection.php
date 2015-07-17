<?php
/**
 * @link http://kimprince.com/starter/starter-application-v10 for usage info
 */
namespace Core\Domain\Collection;

use Core;
use Core\Exception;
use Core\Domain\Collection\iCollection;
use Core\Domain\iDomain;
use Core\Domain\Factory\AbstractFactory;

/**
 * Abstract collection class
 *
 * Members are stored in a raw data array and instantiated if/when accessed.
 */
abstract class AbstractCollection implements \Iterator
{
    use Core\HelperTrait;

    /**
     * @var AbstractFactory
     */
    protected $factory;
        
    /**
     * @var integer
     */
    protected $total = 0;

    /**
     * @var array
     */
    protected $raw = array();
        
    /**
     * @var int
     */
    private $pointer = 0;
    
    /**
     * Members which have already been instantiated
     *
     * @var array
     */
    protected $objects = array();

    /**
     * @var boolean
     */
    protected $readOnly = FALSE;

    /**
     * Initialise the collection
     *
     * @param AbstractFactory $factory
     * @param array $raw Row data
     */
    public function __construct(AbstractFactory $factory, array $raw = null)
    {
        $this->factory = $factory;

        if (!is_null($raw)) {
            $this->raw = $raw;
            $this->total = count($raw);
        }
    }

    /**
     * Add an object to the collection
     *
     * @param iDomain\Domain $object
     * @throws Exception\Collection
     */
    protected function doAdd(iDomain\Domain $object)
    {
        if ($this->isReadOnly()) {            
            throw new Exception\Collection(
                'Read only collection: ' . $this->getShortType($this));
        }

        $this->objects[$this->total] = $object;
        $this->total++;
    }

    /**
     * Returns element at the given index.
     * 
     * @param int $num
     * @return iDomain\Domain
     */
    public function elementAt($num)
    {
        $this->pointer = $num;
        return $this->getRow($this->pointer);
    }

    /**
     * End
     *
     * Move pointer to the end of the collection and return the last element.
     */
    public function end()
    {
        $this->pointer = $this->total - 1;
        return $this->getRow($this->pointer);
    }

    /**
     * Get a row from the collection
     * 
     * Checks the array of objects which have already been instantiated.  On
     * no match, uses the factory to create an object from it's stored row data.
     * 
     * @param int $num
     * @return iDomain\Domain
     */
    protected function getRow($num)
    {
        if ($num >= $this->total || $num < 0 ) {
            return null;
        }

        if (isset($this->objects[$num])) {
            return $this->objects[$num];
        }

        $this->objects[$num] = $this->factory->create($this->raw[$num]);
        return $this->objects[$num];
    }
    
    // iterator methods

    /**
     * Rewind
     * 
     * @return void
     */
    public function rewind()
    {
        $this->pointer = 0;
    }

    /**
     * Current
     * 
     * @return iDomain\Domain
     */
    public function current()
    {
        return $this->getRow($this->pointer);
    }

    /**
     * Key
     * 
     * @return integer
     */
    public function key()
    {
        return $this->pointer;
    }

    /**
     * Next
     * 
     * @return iDomain\Domain
     */
    public function next()
    {
        $row = $this->getRow($this->pointer);

        if ($row) {
            $this->pointer++;
        }

        return $row;
    }

    /**
     * Valid
     * 
     * @return boolean
     */
    public function valid()
    {
        return (!is_null($this->current()));
    }

    /**
     * Count
     * 
     * @return integer
     */
    public function count()
    {
        return $this->total;
    }

    // utility methods

    /**
     * Is read only
     * 
     * @return  boolean
     */
    public function isReadOnly()
    {
        return $this->readOnly;
    }

    /**
     * Set read only
     */
    protected function setReadOnly()
    {
        $this->readOnly = true;
    }

    /**
     * Get raw data
     *
     * @return array
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * Clone
     * 
     * Set clone as read only
     */
    public function __clone()
    {
        $this->setReadOnly();
    }
}