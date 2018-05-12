<?php

namespace AppBundle\Controller\API;

use BPashkevich\UserBundle\Entity\User;
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

    public function saveUserAction(Request $request)
    {
        $id = $request->get('id');
        $user =  new User();
        $user->setUsername($request->get('name'));
        $user->setEmail($request->get('email'));
        $user->setPhoneNumber($request->get('number'));
        $user->setRole($request->get('role'));
        if ($id) {
            $this->userService->editUser($user);
            $user = $this->userService->findUserById(array('id' => $id))[0];
        } else {
            $user = $this->userService->createUser($user);
        }

        return $this->userService->getMainInfo($user);
    }

    public function deleteUserAction(Request $request)
    {
        $id = $request->get('id');
        $user = $this->userService->findUserById(array('id' => $id))[0];
        $this->userService->deleteAttribute($user);

        return true;
    }

    public function deleteALLUsersAction(Request $request)
    {
        $users = $this->userService->getAllUsers();
        foreach ($users as $user) {
            $this->userService->deleteUser($user);
        }

        return true;
    }
}
