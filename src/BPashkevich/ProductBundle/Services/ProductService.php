<?php

namespace BPashkevich\ProductBundle\Services;

use BPashkevich\ProductBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Session\Session;

class ProductService
{
    private $em;

    private $session;

    private $dbService;

    private  $repository;

    public function __construct(\Doctrine\ORM\EntityManager $em, Session $session, DBService $dbService)
    {
        $this->em = $em;
        $this->session = $session;
        $this->dbService = $dbService;
        $this->repository = $this->em->getRepository('BPashkevichProductBundle:Product');
    }

    public function getAllProduct()
    {
        return $this->repository->findAll();
    }

    public function getProductFromCategory($category)
    {
        return  $this->repository->findBy( array('category' => $category));
    }

    public function createProduct(Product $product, array $attributes, array $attributeValues)
    {
        if(count($attributes) == count($attributeValues)){
            $queryBuilder = $this->dbService->getQueryBuilder();
//        $product->setSeller($this->session->get('currentUserName'))
            $this->em->persist($product);
            $this->em->flush();
            for ($i=0; $i<count($attributes); $i++) {
                $queryBuilder->insert('product_attribute_value')
                    ->setValue('product_id', $product->getId())
                    ->setValue('attribute_id', $attributes[$i]->getId())
                    ->setValue('value_id', $attributeValues[$i]->getId());
                $queryBuilder->execute();
            }
            return $product;
        }
        return null;
    }

    public function getAttributesId(Product $product)
    {
        $queryBuilder = $this->dbService->getQueryBuilder();
            $queryBuilder
                ->select('attribute_id')
                ->from('product_attribute_value')
                ->where('product_id = ?')
                ->setParameter(0, $product->getId());
            $sth = $queryBuilder->execute();
            $data = $sth->fetchAll();
            $attrs = [];
            for ($i = 0; $i < count($data); $i++){
                $attrs[$i] = $data[$i]['attribute_id'];
            }

            return $attrs;
    }

    public function getAttributesValuesId(Product $product)
    {
        $queryBuilder = $this->dbService->getQueryBuilder();
        $queryBuilder
            ->select('value_id', 'attribute_id')
            ->from('product_attribute_value')
            ->where('product_id = ?')
            ->setParameter(0, $product->getId());
        $sth = $queryBuilder->execute();
        $data = $sth->fetchAll();
        $values = [];
        foreach ($data as $datum) {
            $values[$datum['attribute_id']] = $datum['value_id'];

        }
        var_dump($values);
        die();

        return $values;
    }

    public function getAttributesValues(Product $product)
    {
        $queryBuilder = $this->dbService->getQueryBuilder();
        $queryBuilder
            ->select('a.name', 'v.value')
            ->from('product_attribute_value', 'p')
            ->innerJoin('p', 'attribute', 'a', 'p.attribute_id=a.id')
            ->innerJoin('p', 'attribute_value', 'v','p.value_id=v.id')
            ->where('p.product_id = ?')
            ->setParameter(0, $product->getId());
        $sth = $queryBuilder->execute();
        $value = $sth->fetchAll();

        return $value;
    }

    public function editProduct(Product $product)
    {
        $this->em->flush();
        return $product;
    }

    public function deleteProduct(Product $product)
    {
        $this->em->remove($product);
        $this->em->flush();
    }
}