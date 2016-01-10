<?php
/**
 * Created by PhpStorm.
 * User: gsull
 * Date: 13-12-2015
 * Time: 1:50
 */
namespace Gulloa\SecurityBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Gulloa\SecurityBundle\Entity\Role;

class LoadRoleData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $roles = array(
          'ROLE_ADMIN',
          'ROLE_USER'
        );
        foreach($roles as $role){
            $newRole = new Role();
            $newRole->setEtiqueta($role);
            $manager->persist($newRole);
        }
        $manager->flush();
    }
}