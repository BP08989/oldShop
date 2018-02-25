<?php

namespace BPashkevich\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConfigurableProduct
 *
 * @ORM\Table(name="configurable_product")
 * @ORM\Entity(repositoryClass="BPashkevich\ProductBundle\Repository\ConfigurableProductRepository")
 */
class ConfigurableProduct
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
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="configurableProduct")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="configurableProduct")
     */
    private $images;

    /**
     * @ORM\ManyToMany(targetEntity="ProductOrder", inversedBy="configurableProduct")
     */
    private $productOrders;

    /**
     * @ORM\ManyToMany(targetEntity="Attribute", inversedBy="configurableProduct")
     */
    private $attribures;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="configurableProduct")
     */
    private $simpleProducts;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->productOrders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->attribures = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set category.
     *
     * @param \BPashkevich\ProductBundle\Entity\Category|null $category
     *
     * @return ConfigurableProduct
     */
    public function setCategory(\BPashkevich\ProductBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return \BPashkevich\ProductBundle\Entity\Category|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add image.
     *
     * @param \BPashkevich\ProductBundle\Entity\Image $image
     *
     * @return ConfigurableProduct
     */
    public function addImage(\BPashkevich\ProductBundle\Entity\Image $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image.
     *
     * @param \BPashkevich\ProductBundle\Entity\Image $image
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeImage(\BPashkevich\ProductBundle\Entity\Image $image)
    {
        return $this->images->removeElement($image);
    }

    /**
     * Get images.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add productOrder.
     *
     * @param \BPashkevich\ProductBundle\Entity\ProductOrder $productOrder
     *
     * @return ConfigurableProduct
     */
    public function addProductOrder(\BPashkevich\ProductBundle\Entity\ProductOrder $productOrder)
    {
        $this->productOrders[] = $productOrder;

        return $this;
    }

    /**
     * Remove productOrder.
     *
     * @param \BPashkevich\ProductBundle\Entity\ProductOrder $productOrder
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductOrder(\BPashkevich\ProductBundle\Entity\ProductOrder $productOrder)
    {
        return $this->productOrders->removeElement($productOrder);
    }

    /**
     * Get productOrders.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductOrders()
    {
        return $this->productOrders;
    }

    /**
     * Add attribure.
     *
     * @param \BPashkevich\ProductBundle\Entity\Attribute $attribure
     *
     * @return ConfigurableProduct
     */
    public function addAttribure(\BPashkevich\ProductBundle\Entity\Attribute $attribure)
    {
        $this->attribures[] = $attribure;

        return $this;
    }

    /**
     * Remove attribure.
     *
     * @param \BPashkevich\ProductBundle\Entity\Attribute $attribure
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAttribure(\BPashkevich\ProductBundle\Entity\Attribute $attribure)
    {
        return $this->attribures->removeElement($attribure);
    }

    /**
     * Get attribures.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttribures()
    {
        return $this->attribures;
    }

    /**
     * Add simpleProduct.
     *
     * @param \BPashkevich\ProductBundle\Entity\Product $simpleProduct
     *
     * @return ConfigurableProduct
     */
    public function addSimpleProduct(\BPashkevich\ProductBundle\Entity\Product $simpleProduct)
    {
        $this->simpleProducts[] = $simpleProduct;

        return $this;
    }

    /**
     * Remove simpleProduct.
     *
     * @param \BPashkevich\ProductBundle\Entity\Product $simpleProduct
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeSimpleProduct(\BPashkevich\ProductBundle\Entity\Product $simpleProduct)
    {
        return $this->simpleProducts->removeElement($simpleProduct);
    }

    /**
     * Get simpleProducts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSimpleProducts()
    {
        return $this->simpleProducts;
    }
}
