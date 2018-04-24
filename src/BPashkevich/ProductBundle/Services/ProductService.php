<?php

namespace BPashkevich\ProductBundle\Services;

use BPashkevich\ProductBundle\Entity\Attribute;
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

    public function findProducts(array $params)
    {
        return $this->repository->findBy($params);
    }

    public function getShortInfo($product, $attr)
    {
        $queryBuilder = $this->dbService->getQueryBuilder();
        $queryBuilder
            ->select('v.value')
            ->from('product_attribute_value', 'p')
            ->innerJoin('p', 'attribute', 'a', 'p.attribute_id=a.id')
            ->innerJoin('p', 'attribute_value', 'v','p.value_id=v.id')
            ->where('p.product_id = ?')
            ->andWhere('a.name = ?')
            ->setParameter(0, $product->getId())
            ->setParameter(1, $attr);
        $sth = $queryBuilder->execute();

        return $sth->fetch()['value'];
    }

    public function getMainInfo(Product $product)
    {
        return array(
            'id' => $product->getId(),
            'name' => $this->getShortInfo($product, 'Name'),
            'price' => $this->getShortInfo($product, 'Price'),
            'shortDescription' => $this->getShortInfo($product, 'Short Description'),
            'img' => $product->getImages()[0]->getUrl()
        );
    }

    public function getSingleProductMainInfo($id)
    {
        $result = array();
        if($id != null) {
            /** @var Product $product */
            $products = $this->findProducts(array('id' => $id, 'configurableProduct' => null));
        } else {
            $products = $this->findProducts(array('configurableProduct' => null));
        }

        foreach ($products as $product) {
            $result[] = array(
                'id' => $product->getId(),
                'name' => $this->getShortInfo($product, 'Name'),
                'price' => $this->getShortInfo($product, 'Price'),
                'shortDescription' => $this->getShortInfo($product, 'Short Description'),
                'img' => $product->getImages()[0]->getUrl()
            );
        }

        return $result;
    }

    public function selectSingleProducts($products)
    {
        $singleProducts = array();
        foreach ($products as $product){
            if($product->getConfigurableProduct() == null){
                $singleProducts[] = $product;
            }
        }

        return $singleProducts;
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

    public function getAttributesValuesId($productId, $attrId)
    {
        $queryBuilder = $this->dbService->getQueryBuilder();
        $queryBuilder
            ->select('value_id', 'value')
            ->from('product_attribute_value', 'p')
            ->innerJoin('p', 'attribute_value', 'v','p.value_id=v.id')
            ->where('p.product_id = ? and p.attribute_id = ?')
            ->setParameter(0, $productId)
            ->setParameter(1, $attrId);

        $sth = $queryBuilder->execute();
        $data = $sth->fetchAll();

//        var_dump($data);
//        die();

        return array($data[0]['value_id'], $data[0]['value']);
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

//            var_dump($value);
//            die();

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