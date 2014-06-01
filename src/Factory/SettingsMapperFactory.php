<?php
namespace HtSettingsModuleDoctrineORM\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use HtSettingsModuleDoctrineORM\Mapper\SettingsMapper;

class SettingsMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new SettingsMapper(
            $serviceLocator->get('HtSettingsModuleDoctrineORM\Doctrine\Em'),
            $serviceLocator->get('HtSettingsModule\Options\ModuleOptions')
        );
    }
}
