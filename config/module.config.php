<?php
return array(
    'ht_settings' => array(   
        'parameter_entity_class' => 'HtSettingsModuleDoctrineORM\Entity\Parameter',
    ),
    'doctrine' => array(
        'driver' => array(
            'HtSettingsModuleDoctrineORM\Entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'paths' => __DIR__ . '/xml/HtSettingsModuleDoctrineORM/'
            ),
            'HtSettingsModule\Entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'paths' => __DIR__ . '/xml/HtSettingsModule/'
            ),
            'orm_default' => array(
                'drivers' => array(
                    'HtSettingsModuleDoctrineORM\Entity'  => 'HtSettingsModuleDoctrineORM\Entity',
                    'HtSettingsModule\Entity' => 'HtSettingsModule\Entity'
                )
            )
        )
    ),
);
