<?php

namespace AppBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BPashkevich\UserBundle\Services\UserService;
use Symfony\Component\HttpFoundation\Request;

class UserAPIController extends Controller
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getALLUsersAction()
    {
        $users = $this->userService->getAllUsers();
        $usersMainInfo = array();
        foreach ($users as $user) {
            $usersMainInfo[] = $this->userService->getMainInfo($user);
        }

        return $usersMainInfo;
    }

    public function getUserByIdAction(Request $request)
    {
        $user = $this->userService->getUserById(array('id' => $request->get('id')));

        return $this->userService->getMainInfo($user);
    }

}
