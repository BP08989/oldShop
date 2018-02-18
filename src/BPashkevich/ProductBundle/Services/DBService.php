<?php

namespace BPashkevich\ProductBundle\Services;

use BPashkevich\ProductBundle\Entity\Image;

class DBService
{

    private  $queryBuilder;

    public function __construct()
    {
        $config = new \Doctrine\DBAL\Configuration();
        $connectionParams = array(
            'dbname' => 'oldShop',
            'user' => 'root',
            'password' => 'root',
            'host' => 'localhost',
            'driver' => 'pdo_mysql',
        );
        $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        $this->queryBuilder = $conn->createQueryBuilder();
    }

    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }
}