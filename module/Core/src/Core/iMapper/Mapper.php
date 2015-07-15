<?php
/**
 * @link http://kimprince.com/starter/starter-application-v10 for usage info
 */
namespace Core\iMapper;

/**
 * Mapper interface
 */
interface Mapper {

    /**
     * Find
     *
     * @param $id
     * @return mixed
     */
    public function find($id);

}