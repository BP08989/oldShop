<?php

namespace BPashkevich\ProductBundle\Services;

use BPashkevich\ProductBundle\Entity\AttributeValue;

class AttributeValueService
{
    private $em;

    private  $repository;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository('BPashkevichProductBundle:AttributeValue');
    }

    public function getAllAttributeValues()
    {
        return $this->repository->findAll();
    }

    public function findAttributeValues(array $params)
    {
        return $this->repository->findBy($params);
    }

    public function createAttributeValue(AttributeValue $attributeValue)
    {
        $this->em->persist($attributeValue);
        $this->em->flush();
        return $attributeValue;
    }

    public function editAttributeValue(AttributeValue $attributeValue)
    {
        $this->em->flush();
        return $attributeValue;
    }

    public function deleteAttributeValue(AttributeValue $attributeValue)
    {
        $this->em->remove($attributeValue);
        $this->em->flush();
    }
}