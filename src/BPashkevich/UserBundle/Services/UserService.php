<?php

namespace BPashkevich\UserBundle\Services;

use BPashkevich\UserBundle\Entity\User;

class UserService
{
    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function getAllUsers()
    {
        return $this->em->getRepository('BPashkevichUserBundle:User')->findAll();
    }

    public function getUserById($id){
        return $this->em->getRepository('BPashkevichUserBundle:User')->find($id);
    }

    public function getMainInfo(User $user)
    {
        return array(
            'id' => $user->getId(),
            'name' => $user->getUsername(),
            'email' => $user->getEmail(),
            'number' => $user->getPhoneNumber()
        );
    }


    public function findUserByUsername($username){
        return $this->em->getRepository('BPashkevichUserBundle:User')->findOneBy(array(
            'username' => $username,
        ));
    }

    public function checkExistingUserFild($user){
        if($this->em->getRepository('BPashkevichUserBundle:User')->findOneBy(array(
            'username' => $user->getUsername(),
        )) ||
        $this->em->getRepository('BPashkevichUserBundle:User')->findOneBy(array(
            'email' => $user->getEmail(),
        )) ||
        $this->em->getRepository('BPashkevichUserBundle:User')->findOneBy(array(
             'phoneNumber' => $user->getPhoneNumber(),
        ))
        ){
            return false;
        }
        return true;

    }

    public function createCategory(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    public function editCategory(User $user)
    {
        $this->em->flush();
        return $user;
    }

    public function deleteCategory(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }
}