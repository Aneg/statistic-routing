<?php

namespace v1\Factory;

use Doctrine\ORM\EntityManager;
use v1\Service\ParserData;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ParserDataFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed|ParserData
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EntityManager $em */
        $em = $serviceLocator->get('doctrine.entitymanager.orm_default');
        return new ParserData($em);
    }
}