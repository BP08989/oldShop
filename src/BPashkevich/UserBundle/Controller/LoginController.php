<?php

namespace BPashkevich\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends Controller
{
    private $authenticationUtils;

    public function __construct(AuthenticationUtils $authenticationUtils)
    {
        $this->authenticationUtils = $authenticationUtils;
    }

    public function loginAction(Request $request)
    {
        $errors = $this->authenticationUtils->getLastAuthenticationError();

        $lastUserName = $this->authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', array(
            'errors' => $errors,
            'last_username' => $lastUserName,
            'categories' => $this->get('b_pashkevich_product.category_srvice')->getAllCategories(),
        ));
    }

    public function logoutAction()
    {

    }


}
