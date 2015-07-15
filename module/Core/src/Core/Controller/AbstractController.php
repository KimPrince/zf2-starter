<?php
/**
 * @link http://kimprince.com/starter/starter-application-v10 for usage info
 */
namespace Core\Controller;

use Core;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Abstract Controller
 */
abstract class AbstractController extends AbstractActionController
{
    use Core\HelperTrait;
}