<?php

namespace Mapper;

class Module
{
    public function getConfig()
    {
        return array(
            'service_manager' => array(
                'abstract_factories' => array(
                    'Mapper\Mapper\MapperLoader'
                ),
            ),
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
