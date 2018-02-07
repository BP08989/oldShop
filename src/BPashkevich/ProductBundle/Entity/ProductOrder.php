<?php

namespace BPashkevich\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductOrder
 *
 * @ORM\Table(name="product_order")
 * @ORM\Entity(repositoryClass="BPashkevich\ProductBundle\Repository\ProductOrderRepository")
 */
class ProductOrder
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="productOrders")
     */
    private $products;

    /**
     * @ORM\ManyToOne(targetEntity="BPashkevich\UserBundle\Entity\User", inversedBy="productOrder")
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add product.
     *
     * @param \BPashkevich\ProductBundle\Entity\Product $product
     *
     * @return ProductOrder
     */
    public function addProduct(\BPashkevich\ProductBundle\Entity\Product $product)
    {
        $product->addProductOrder($this);
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product.
     *
     * @param \BPashkevich\ProductBundle\Entity\Product $product
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProduct(\BPashkevich\ProductBundle\Entity\Product $product)
    {
        return $this->products->removeElement($product);
    }

    /**
     * Get products.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set users.
     *
     * @param \BPashkevich\UserBundle\Entity\User|null $users
     *
     * @return ProductOrder
     */
    public function setUsers(\BPashkevich\UserBundle\Entity\User $users = null)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get users.
     *
     * @return \BPashkevich\UserBundle\Entity\User|null
     */
    public function getUsers()
    {
        return $this->users;
    }
}
