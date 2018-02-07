<?php

namespace BPashkevich\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BPashkevichUserBundle:Default:index.html.twig');
    }
}
