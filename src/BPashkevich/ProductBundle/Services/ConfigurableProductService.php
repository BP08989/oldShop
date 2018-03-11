<?php

namespace BPashkevich\ProductBundle\Services;

use BPashkevich\ProductBundle\Entity\Attribute;
use BPashkevich\ProductBundle\Entity\ConfigurableProduct;
use BPashkevich\ProductBundle\Entity\Product;

class ConfigurableProductService
{
    private $em;

    private $dbService;

    private  $repository;

    private $productService;

    public function __construct(\Doctrine\ORM\EntityManager $em, DBService $dbService, ProductService $productService)
    {
        $this->em = $em;
        $this->dbService = $dbService;
        $this->repository = $this->em->getRepository('BPashkevichProductBundle:ConfigurableProduct');
        $this->productService = $productService;
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

    public function getSelectedAttributesId(ConfigurableProduct $product)
    {
        $queryBuilder = $this->dbService->getQueryBuilder();
        $queryBuilder
            ->select('attribute_id')
            ->from('configurable_product_attribute_value')
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

    public function getShortInfo(ConfigurableProduct $product, $attr)
    {
        $queryBuilder = $this->dbService->getQueryBuilder();
        $queryBuilder
            ->select('v.value')
            ->from('configurable_product_attribute_value', 'p')
            ->innerJoin('p', 'attribute', 'a', 'p.attribute_id=a.id')
            ->innerJoin('p', 'attribute_value', 'v','p.value_id=v.id')
            ->where('p.product_id = ?')
            ->andWhere('a.name = ?')
            ->setParameter(0, $product->getId())
            ->setParameter(1, $attr);
        $sth = $queryBuilder->execute();

        return $sth->fetch()['value'];
    }

    public function getAttributesValues(ConfigurableProduct $product)
    {
        $queryBuilder = $this->dbService->getQueryBuilder();
        $queryBuilder
            ->select('a.name', 'v.value')
            ->from('configurable_product_attribute_value', 'p')
            ->innerJoin('p', 'attribute', 'a', 'p.attribute_id=a.id')
            ->innerJoin('p', 'attribute_value', 'v','p.value_id=v.id')
            ->where('p.product_id = ?')
            ->setParameter(0, $product->getId());
        $sth = $queryBuilder->execute();
        $value = $sth->fetchAll();

        return $value;
    }

    public function getNotMandatoryAttributes(ConfigurableProduct $product)
    {
        $queryBuilder = $this->dbService->getQueryBuilder();
        $queryBuilder
            ->select('distinct a.name', 'a.id as attribute_id', 'v.id', 'v.value')
            ->from('configurable_product_attribute', 'cpa')
            ->innerJoin('cpa', 'attribute', 'a', 'cpa.attribute_id=a.id')
            ->innerJoin('cpa', 'attribute_value', 'v', 'cpa.attribute_id=v.attribute_id')
            ->where('cpa.configurable_product_id = ?')
            ->where('a.mandatory = 0')
            ->setParameter(0, $product->getId());
        $sth = $queryBuilder->execute();
        $value = $sth->fetchAll();
        $attr = [];

        $counter = 0;
        $number = 0 ;
        foreach ($value as  $val){
            if ($counter != 0){
                if ($value[$counter-1]['name'] != $val['name']){
                    $number=0;
                }
                else{
                    $number++;
                }
            }
            $attr[$val['name']][$number] = ['id' => $val['id'],'value' => $val['value'], 'attribute_id' => $val['attribute_id']];

            $counter++;
        }

//        var_dump($attr);
//        die();

        return $attr;
    }

    public function getSimplesParams(ConfigurableProduct $configurableProduct){
        $simpleProducts = $configurableProduct->getSimpleProducts();
        $options = array();
        $allowedAttributes = array();
        $allowedValues = array();
        $images = array();
        $productsByKey = array();

        /** @var Product $simpleProduct */
        foreach ($simpleProducts as $simpleProduct){
            $key = '';
            $names = array();
            /** @var Attribute $attribute */
            foreach ($configurableProduct->getAttribures() as $attribute){
                list($value_id, $value) = $this->productService->getAttributesValuesId($simpleProduct->getId(), $attribute->getId());
                if (!$attribute->getMandatory()){
                    $key .= $value_id . ',';
                    $allowedValues[$attribute->getId()][$value_id] = $value;
                    $allowedAttributes[$attribute->getId()] = $attribute->getName();
                }
                else{
                    $names[$attribute->getName()] =$value;
                }
            }
            $productsByKey[$key] = $simpleProduct->getId();
            $options[$key] = $names;
            $images[$key] = $simpleProduct->getImages()[0]->getUrl();
        }

        $params = array(
            'options' => $options,
            'allowedAttributes' => $allowedAttributes,
            'allowedValue' => $allowedValues,
            'images' => $images,
            'products' => $productsByKey,
        );

//        die(var_dump('params = ',  $params));

        return $params;
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