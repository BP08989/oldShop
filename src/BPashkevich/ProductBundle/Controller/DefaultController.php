<?php

namespace BPashkevich\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BPashkevichProductBundle:Default:index.html.twig');
    }
}
