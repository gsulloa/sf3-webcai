<?php
namespace Cai\ApiBundle\Security;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class ApiKeyUserProvider implements UserProviderInterface
{
    private $em;
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getUsernameForApiKey($apiKey)
    {
        // Look up the username based on the token in the database, via
        // an API call, or do something entirely different
        $user = $this->em->getRepository('GulloaSecurityBundle:User')->findOneByToken($apiKey);
        return $user === null ? false : $user->getUsername();
    }

    public function loadUserByUsername($username)
    {

        return $this->em->getRepository('GulloaSecurityBundle:User')->findOneByUsername($username);;
    }

    public function refreshUser(UserInterface $user)
    {
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return 'Gulloa\SecurityBundle\Entity\User' === $class;
    }
}