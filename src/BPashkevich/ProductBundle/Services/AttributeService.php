<?php

namespace BPashkevich\ProductBundle\Services;

use BPashkevich\ProductBundle\Entity\Attribute;

class AttributeService
{
    private $em;

    private  $repository;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository('BPashkevichProductBundle:Attribute');
    }

    public function getAllAttributes()
    {
        return $this->repository->findAll();
    }

    public function getMainInfo(Attribute $attribute)
    {
        return array(
            'id' => $attribute->getId(),
            'name' => $attribute->getName(),
            'code' => $attribute->getCode(),
            'mondatory' => $attribute->getMandatory()
        );
    }

        public function findAttributes(array $params)
        {
            return $this->repository->findBy($params);
        }

    public function createAttribute(Attribute $attribute)
    {
        $this->em->persist($attribute);
        $this->em->flush();
        return $attribute;
    }

    public function editAttribute(Attribute $attribute)
    {
        $this->em->flush();
        return $attribute;
    }

    public function deleteAttribute(Attribute $attribute)
    {
        $this->em->remove($attribute);
        $this->em->flush();
    }
}