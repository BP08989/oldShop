<?php

namespace BPashkevich\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="BPashkevich\ProductBundle\Repository\ImageRepository")
 */
class Image
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
     * @var string|null
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="image")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="ConfigurableProduct", inversedBy="image")
     */
    private $configurableProduct;


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
     * Set url.
     *
     * @param string|null $url
     *
     * @return Image
     */
    public function setUrl($url = null)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set product.
     *
     * @param \BPashkevich\ProductBundle\Entity\Product|null $product
     *
     * @return Image
     */
    public function setProduct(\BPashkevich\ProductBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product.
     *
     * @return \BPashkevich\ProductBundle\Entity\Product|null
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set configurableProduct.
     *
     * @param \BPashkevich\ProductBundle\Entity\ConfigurableProduct|null $configurableProduct
     *
     * @return Image
     */
    public function setConfigurableProduct(\BPashkevich\ProductBundle\Entity\ConfigurableProduct $configurableProduct = null)
    {
        $this->configurableProduct = $configurableProduct;

        return $this;
    }

    /**
     * Get configurableProduct.
     *
     * @return \BPashkevich\ProductBundle\Entity\ConfigurableProduct|null
     */
    public function getConfigurableProduct()
    {
        return $this->configurableProduct;
    }
}
