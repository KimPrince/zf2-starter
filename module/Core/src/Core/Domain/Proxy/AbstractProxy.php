<?php
/**
 * @link http://kimprince.com/starter/starter-application-v10 for usage info
 */
namespace Core\Domain\Proxy;

use Core;
use Core\iMapper\Mapper;
use Core\Exception;
use Core\Domain\AbstractDomain;

/**
 * Abstract proxy class
 */
abstract class AbstractProxy
{
    use Core\HelperTrait;

    /**
     * @var integer
     */
    protected $id = null;

    /**
     * @var Mapper
     */
    protected $mapper = null;

    /**
     * @var Core\Domain\AbstractDomain
     */
    protected $subject = null;

    /**
     * Constructor
     *
     * @param integer $id Object identifier
     * @param Mapper $mapper Used to retrieve the object
     */
    public function __construct($id, Mapper $mapper)
    {
        $this->id = $id;
        $this->mapper = $mapper;
    }

    /**
     * Get subject
     *
     * @throws Exception\Proxy
     * @return AbstractDomain
     */
    protected function getSubject()
    {
        if (!$this->subject) {

            if (!($this->subject = $this->mapper->find($this->id))) {
                throw new Exception\Proxy('Unable to find subject for proxy: ' . get_class($this) .
                    ', With ID: ' . $this->getId());
            }
        }

        return $this->subject;
    }

    /**
     * Get property
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        if ($property == 'id') {
            return $this->getId();
        }

        return $this->getSubject()->$property;
    }

    /**
     * Set property
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value)
    {
        $this->getSubject()->$property = $value;
    }

    /**
     * Is set
     *
     * @param $property
     * @return boolean
     */
    public function __isset($property)
    {
        if ($property == 'id') {
            return isset($this->id);
        }

        return isset($this->getSubject()->$property);
    }

    /**
     * Unset property
     *
     * @param string $property
     * @return mixed
     */
    public function __unset($property)
    {
        unset($this->getSubject()->$property);
    }

    /**
     * Call method
     *
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function __call($method, array $params)
    {
        return call_user_func_array(array($this->getSubject(), $method), $params);
    }

    /**
     * Get id
     *
     * Saves having to retrieve the entire object when only the ID is required.
     *
     * @return integer|string
     */
    public function getId()
    {
        return $this->id;
    }
}