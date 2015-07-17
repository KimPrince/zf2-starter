<?php
/**
 * @link http://kimprince.com/starter/starter-application-v10 for usage info
 */
namespace Core\Domain\Collection\iCollection;

use Core\Domain\iDomain\Domain;

/**
 * Collection interface
 */
interface Collection extends \Countable
{
    // Interfaces that extend Collection should declare an add() method
    // For example, the Foo collection should declare the following method:
    // public function add(\Core\Domain\iDomain\Foo $foo);

    /**
     * Get element at
     * 
     * @param integer $index The pointer location
     * @return Domain
     */
    public function elementAt($index);

    /**
     * End
     *
     * Move pointer to end and return last value
     *
     * @return Domain
     */
    public function end();
}