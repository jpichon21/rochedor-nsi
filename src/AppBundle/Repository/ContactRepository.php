<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Contact;

class ContactRepository
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
    * Find Contact by its Id
    *
    * @param int $contactId
    * @return Contact
    */
    public function findContact($contactId)
    {
        $query = $this->entityManager
        ->createQuery('SELECT c FROM AppBundle\Entity\Contact c WHERE c.codco=:contactId');
        $query->setParameter('contactId', $contactId);
        return $query->getOneOrNullResult();
    }

     /**
    * Find Contact by its personnal infos
    *
    * @param string $lastname
    * @param string $firstname
    * @param \DateTime $birthdate
    * @return Contact
    */
    public function findContactByInfos($lastname, $firstname, $birthdate)
    {
        $query = $this->entityManager
        ->createQuery('SELECT c FROM AppBundle\Entity\Contact c 
        WHERE UPPER(c.nom)=UPPER(:lastname) AND UPPER(c.prenom)=UPPER(:firstname) AND c.datnaiss=:birthdate');
        $query->setParameters(['lastname' => $lastname, 'firstname' => $firstname, 'birthdate' => $birthdate]);
        $query->setMaxResults(1);
        return $query->getOneOrNullResult();
    }
    
    /**
     * Find ContactL by contact and parent
     *
     * @param int $contactId
     * @param int $parentId
     * @return ContactL
     */
    public function findContactL($contactId, $parentId)
    {
        $query = $this->entityManager
        ->createQuery('SELECT c FROM AppBundle\Entity\ContactL c WHERE c.col=:contactId AND c.colp=:parentId');
        $query->setParameters(['contactId' => $contactId, 'parentId' => $parentId]);
        $query->setMaxResults(1);
        return $query->getOneOrNullResult();
    }

    /**
    * Find Contact by its username
    *
    * @param string $username
    * @return Contact
    */
    public function findContactByUsername($username)
    {
        $query = $this->entityManager
        ->createQuery('SELECT c FROM AppBundle\Entity\Contact c WHERE c.username=:username OR c.username=:username');
        $query->setParameter('username', $username);
        $query->setMaxResults(1);
        return $query->getOneOrNullResult();
    }

    /**
     * Check if username is unique
     *
     * @param string $username
     * @param int $codco
     * @return boolean
     */
    public function isUsernameUnique($username, $codco = null)
    {
        $c = $this->findContactByUsername($username);
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
    * Find Contact by reset_token
    *
    * @param string $token
    * @return Contact
    */
    public function findContactByToken($token)
    {
        $query = $this->entityManager
        ->createQuery('SELECT c FROM AppBundle\Entity\Contact c 
        WHERE c.resetToken=:token AND c.resetTokenExpiresAt>:now');
        $query->setParameters(['token' => $token, 'now' => new \DateTime()]);
        return $query->getOneOrNullResult();
    }
}
