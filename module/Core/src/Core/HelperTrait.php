<?php
/**
 * @link http://kimprince.com/starter/starter-application-v10 for usage info
 */
namespace Core;

/**
 * Helper trait
 */
trait HelperTrait
{
    /**
     * Get short type
     *
     * Returns the unqualified class name.
     *
     * @param Object|string $type
     * @return string
     */
    public function getShortType($type)
    {
        if (is_object($type)) {
            $type = get_class($type);
        }

        return substr(strrchr($type, '\\'),1);
    }
}