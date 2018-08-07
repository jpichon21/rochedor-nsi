<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Client;

class ClientRepository
{
    
    /**
    * @var EntityRepository
    */
    private $repository;
    
    /**
    * @var EntityManagerInterface
    */
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
    * Find Client by its Id
    *
    * @param int $clientId
    * @return Client
    */
    public function findClient($clientId)
    {
        $query = $this->entityManager
        ->createQuery('SELECT c FROM AppBundle\Entity\Client c WHERE c.codcli=:clientId');
        $query->setParameter('clientId', $clientId);
        return $query->getOneOrNullResult();
    }

    /**
    * Find Client by its email
    *
    * @param string $email
    * @return Client
    */
    public function findClientByEmail($email)
    {
        $query = $this->entityManager
        ->createQuery('SELECT c FROM AppBundle\Entity\Client c WHERE c.email=:email');
        $query->setParameter('email', $email);
        $query->setMaxResults(1);
        return $query->getOneOrNullResult();
    }

    /**
    * Find Client by reset_token
    *
    * @param string $token
    * @return Client
    */
    public function findClientByToken($token)
    {
        $query = $this->entityManager
        ->createQuery('SELECT c FROM AppBundle\Entity\Client c 
        WHERE c.resetToken=:token AND c.resetTokenExpiresAt>:now');
        $query->setParameters(['token' => $token, 'now' => new \DateTime()]);
        return $query->getOneOrNullResult();
    }
}
