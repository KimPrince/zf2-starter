<?php
/**
 * @link http://kimprince.com/starter/starter-application-v10 for usage info
 */
namespace Core\Domain;

use Core;
use Core\Exception;

/**
 * Abstract domain class
 */
abstract class AbstractDomain
{
    use Core\HelperTrait;

    /**
     * Keys are the names of allowed fields.  Values are boolean where
     * true = required, false = optional
     *
     * @var array
     */
    protected $allowed = array();

    /**
     * Actual property values
     *
     * @var array
     */
    protected $data = array();

    /**
     * Domain object factories
     *
     * @var array
     */
    protected $factories = array();

    /**
     * Mappers, for use as finders only
     *
     * @var array
     */
    protected $finders = array();

    /**
     * Domain object constructor
     *
     * Set property values and check all required fields.  Store factories and finders.
     *
     * @param array $values Object properties
     * @param array $factories Domain object factories
     * @param array $finders Mappers
     * @throws Exception\InvalidInput
     */
    public function __construct(array $values = array(), array $factories = array(), array $finders = array())
    {
        foreach ($values as $field => $value) {

            if (isset($value)) {
                if (!array_key_exists($field, $this->allowed)) {
                    throw new Exception\InvalidInput(
                        "Can not set $field on construction for " . get_class($this));
                }

                $this->data[$field] = $value;
            }
        }

        $this->checkRequired();

        $this->factories = $factories;
        $this->finders   = $finders;
    }

    /**
     * Default property setter
     *
     * By default properties CAN NOT be updated by client code.  This may be overridden by implementing
     * a custom setter with a name of setPropertyName.
     *
     * @param string $field
     * @param mixed $value
     * @throws Exception\InvalidInput
     */
    public function __set($field, $value)
    {
        $method = 'set' . ucfirst($field);

        if (method_exists($this, $method)) {
            $this->{$method}($value);
        } else {
            throw new Exception\InvalidInput(
                "Can not update $field for " . get_class($this));
        }
    }

    /**
     * Default property getter
     *
     * By default properties CAN be read by client code.  This may be overridden by implementing a
     * custom getter with a name of getPropertyName.  Properties which are allowed but are not set
     * return null.
     *
     * @param string $field
     * @throws Exception\Exception
     * @return mixed
     */
    public function __get($field)
    {
        $method = 'get' . ucfirst($field);

        if (method_exists($this, $method)) {
            return $this->{$method}();
        } elseif (!array_key_exists($field, $this->allowed)) {
            throw new Exception\Exception(
                "Field  $field is not allowed for " . get_class($this));
        } elseif (!array_key_exists($field, $this->data)) {
            return null;
        }

        return $this->data[$field];
    }

    /**
     * Isset
     *
     * @param string $field
     * @return boolean
     */
    public function __isset($field)
    {
       return isset($this->data[$field]);
    }

    /**
     * Unset
     *
     * @param string $field
     */
    public function __unset($field)
    {
        if (isset($this->data[$field])) {
            unset($this->data[$field]);
        }
    }

    /**
     * Set identity
     *
     * The default custom identity setter.  Allows identity to be set once only.
     *
     * @param integer $id
     * @throws Exception\Exception
     */
    public function setId($id)
    {
        if (isset($this->data['id']) && $this->data['id'] != $id) {
            throw new Exception\Exception('Identity cannot be updated for ' . get_class());
        }

        $this->data['id'] = $id;
    }

    /**
     * Get identity
     *
     * The default custom identity getter.
     *
     * @return mixed|null
     */
    public function getId()
    {
        return isset($this->data['id']) ? $this->data['id'] : null;
    }

    /**
     * Check required properties
     *
     * @throws Exception\InvalidInput
     */
    protected function checkRequired()
    {
        foreach ($this->allowed as $field => $required) {
            if ($required && !(isset($this->data[$field]))) {
                throw new Exception\InvalidInput(
                    "$field is a minimum property for " . get_class($this));
            }
        }
    }
}