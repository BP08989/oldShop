<?php

namespace BPashkevich\ProductBundle\Services;

use BPashkevich\ProductBundle\Entity\ConfigurableProduct;

class ConfigurableProductService
{
    private $em;

    private $dbService;

    private  $repository;

    public function __construct(\Doctrine\ORM\EntityManager $em, DBService $dbService)
    {
        $this->em = $em;
        $this->dbService = $dbService;
        $this->repository = $this->em->getRepository('BPashkevichProductBundle:ConfigurableProduct');
    }

    public function getAllProduct()
    {
        return $this->repository->findAll();
    }

    public function getProductFromCategory($category)
    {
        return  $this->repository->findBy( array('category' => $category));
    }

    public function findProducts(array $params)
    {
        return $this->repository->findBy($params);
    }

    public function createProduct(ConfigurableProduct $product, array $attributes, array $attributeValues)
    {
        if(count($attributes) == count($attributeValues)){
            $queryBuilder = $this->dbService->getQueryBuilder();
//        $product->setSeller($this->session->get('currentUserName'))
            $this->em->persist($product);
            $this->em->flush();
            for ($i=0; $i<count($attributes); $i++) {
                $queryBuilder->insert('configurable_product_attribute_value')
                    ->setValue('product_id', $product->getId())
                    ->setValue('attribute_id', $attributes[$i]->getId())
                    ->setValue('value_id', $attributeValues[$i]->getId());
                $queryBuilder->execute();
            }
            return $product;
        }
        return null;
    }

    public function editProduct(ConfigurableProduct $product)
    {
        $this->em->flush();
        return $product;
    }

    public function deleteProduct(ConfigurableProduct $product)
    {
        $this->em->remove($product);
        $this->em->flush();
    }
}