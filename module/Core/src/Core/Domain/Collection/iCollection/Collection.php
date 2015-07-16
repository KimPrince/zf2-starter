<?php
/**
 * @link http://kimprince.com/starter/starter-application-v10 for usage info
 */
namespace Core\Domain\Collection\iCollection;

use Core\Domain\iDomain\Domain;

/**
 * Collection interface
 */
interface Collection
{
    /**
     * Add member
     *
     * @param Domain $domain
     */
    public function add(Domain $domain);
            
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