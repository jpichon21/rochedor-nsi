<?php
namespace AppBundle\Repository;

use AppBundle\Entity\ContactL;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Contact;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Tools\Pagination\Paginator;

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
    * @param string $email
    * @param string $lastname
    * @param string $firstname
    * @return Contact
    */
    public function findContactByInfos($email, $lastname, $firstname)
    {
     
        $firstname = str_replace(['-', ' '], '', $firstname);
        $lastname = str_replace(['-', ' '], '', $lastname);
        $query = $this->entityManager
        ->createQuery(
            'SELECT c FROM AppBundle\Entity\Contact c 
            WHERE REPLACE(REPLACE(UPPER(c.nom), \'-\', \'\'), \' \', \'\')=UPPER(:lastname)
            AND REPLACE(REPLACE(UPPER(c.prenom), \'-\', \'\'), \' \', \'\')=UPPER(:firstname)
            AND c.email=:email
            AND c.username <> \'\'
            AND c.username IS NOT NULL
            ORDER BY c.codco'
        )->setMaxResults(1);
        $query->setParameters(['lastname' => $lastname, 'firstname' => $firstname, 'email' => $email]);
        return $query->getResult();
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
    * Find Contact by its username
    *
    * @param string $username
    * @return Contact
    */
    public function findContactByEmail($email)
    {
        $query = $this->entityManager
        ->createQuery('SELECT c FROM AppBundle\Entity\Contact c WHERE c.email=:email');
        $query->setParameter('email', $email);
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

    public function findParents($contactId)
    {
        $query = $this->entityManager->createQuery(
            'SELECT DISTINCT co.nom, co.prenom, co.codco, co.ident, co.civil,
            co.civil2, co.adresse, co.cp, co.ville, co.pays, co.tel,
            co.mobil, co.email, co.profession, co.datnaiss, col.coltyp, col.colt, col.colp
            FROM AppBundle\Entity\ContactL col
            JOIN AppBundle\Entity\Contact co WITH co.codco=col.col
            WHERE col.colp =:contactId 
            AND col.colt <> :accom
            AND col.colp <> col.col'
        );
        $query->setParameters(['contactId' => $contactId, 'accom' => 'accom']);
        return $query->getResult();
    }

    /**
     * Find Contact list for cms' backoffice
     *
     * @return ArrayCollection
     */
    public function findAllUser()
    {
        $query = $this->entityManager
        ->createQuery('
        SELECT c FROM AppBundle\Entity\Contact c 
        WHERE c.roles LIKE :role
        ')
        ->setParameter('role', '%ROLE_ADMIN%')
        ->setMaxResults(100);
        return $query->getResult();
    }

    /**
     * @param $codCo
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function setTypCoDonateur($codCo)
    {
        $query = $this->entityManager->getConnection()
            ->query('UPDATE contact c SET c.TypCo = (c.TypCo | 2) WHERE c.CodCo = ' . $codCo);

        return $query->execute();
    }
}
