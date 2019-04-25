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
    * Find Client by its username
    *
    * @param string $username
    * @return Client
    */
    public function findClientByUsername($username)
    {
        $query = $this->entityManager
        ->createQuery('SELECT c FROM AppBundle\Entity\Client c WHERE c.username=:username');
        $query->setParameter('username', $username);
        $query->setMaxResults(1);
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
     * Check if email is unique
     *
     * @param string $email
     * @param int $codco
     * @return boolean
     */
    public function isEmailUnique($email, $codco = null)
    {
        $c = $this->findClientByEmail($email);
        if ($c === null) {
            return true;
        }
        if ($codco === null) {
            return false;
        }
        if ($c->getCodco() !== $codco) {
            return false;
        }
        return true;
    }

    /**
    * Find Client by its personnal infos
    *
    * @param string $email
    * @param string $lastname
    * @param string $firstname
    * @return Client
    */
    public function findClientByInfos($email, $lastname, $firstname)
    {
     
        $firstname = str_replace(['-', ' '], '', $firstname);
        $lastname = str_replace(['-', ' '], '', $lastname);
        $query = $this->entityManager
        ->createQuery(
            'SELECT c FROM AppBundle\Entity\Client c 
            WHERE REPLACE(REPLACE(UPPER(c.nom), \'-\', \'\'), \' \', \'\')=UPPER(:lastname)
            AND REPLACE(REPLACE(UPPER(c.prenom), \'-\', \'\'), \' \', \'\')=UPPER(:firstname)
            AND c.email=:email
            AND c.username <> \'\'
            AND c.username IS NOT NULL'
        );
        $query->setParameters(['lastname' => $lastname, 'firstname' => $firstname, 'email' => $email]);
        return $query->getResult();
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
