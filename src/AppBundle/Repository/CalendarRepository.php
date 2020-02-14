<?php
namespace AppBundle\Repository;

use AppBundle\Entity\Base;
use AppBundle\Entity\Tables;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Contact;
use AppBundle\Entity\ContactL;

class CalendarRepository
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
        $this->repositoryBase = $entityManager->getRepository(Base::class);
        $this->repositoryTable = $entityManager->getRepository(Tables::class);
        $this->repositoryContact = $entityManager->getRepository(Contact::class);
        $this->entityManager = $entityManager;
    }

    public function findCalendar($calendarId)
    {
        $query = $this->entityManager
        ->createQuery('SELECT a.libact, a.sitact, c.datdeb, c.datfin
        FROM AppBundle\Entity\Calendrier c 
        JOIN AppBundle\Entity\Activite a WITH a.codact = c.codb
        WHERE c.codcal=:calendarId');
        $query->setParameter('calendarId', $calendarId);
        return $query->getOneOrNullResult();
    }

    
    public function findRegistrationCount($site)
    {
        $query = $this->entityManager
        ->createQuery('SELECT v.valeurn FROM AppBundle\Entity\Variable v WHERE v.nom=:varName');
        $query->setParameter('varName', $this->insVariable($site));
        return $query->getOneOrNullResult();
    }


    public function updateRegistrationCounter($site, $value)
    {
        $query = $this->entityManager
        ->createQuery('SELECT v FROM AppBundle\Entity\Variable v WHERE v.nom=:varName');
        $query->setParameter('varName', $this->insVariable($site));
        $v = $query->getOneOrNullResult();
        $v->setValeurn($value);
        $this->entityManager->persist($v);
        $this->entityManager->flush();
    }
    
    
    private function insVariable($site)
    {
        $now = new \DateTime();
        $varName = 'ins';
        $varName .= strtoupper(substr($site, 0, 1));
        $varName .= $now->format('y');
        return $varName;
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
        return $query->getOneOrNullResult();
    }
    
    public function findSpeakers()
    {
        $query = $this->entityManager->createQuery(
            'SELECT DISTINCT CONCAT(co.prenom, \' \', UPPER(co.nom)) AS name , co.codco AS value, co.civil
            FROM AppBundle\Entity\Contact co
            INNER JOIN AppBundle\Entity\CalL cal WITH co.codco=cal.lcal AND cal.typlcal=:typlcal 
            INNER JOIN AppBundle\Entity\Calendrier ca WITH ca.codcal=cal.codcal 
            INNER JOIN AppBundle\Entity\Activite a WITH a.codact=ca.codb 
            WHERE ca.datdeb>=:start AND a.divact=:divact 
            ORDER BY name'
        );
        $query->setParameters(['start' => new \DateTime(), 'divact' => 'RET', 'typlcal' => 'coAct']);
        return $query->getResult();
    }
    
    public function findTranslations()
    {
        // Récupération de toutes les traductions existantes
        $query = $this->entityManager->createQuery(
            'SELECT t.tref AS value, t.tlib AS name
                FROM AppBundle\Entity\Tables t
                WHERE t.tlien=34
                GROUP BY t.tref, t.tlib'
        );
        $existingTranslations = $query->getResult();

        // Récupération de toutes les traductions liées au calendrier des retraites
        $query = $this->entityManager->createQuery(
            'SELECT ca.langue
            FROM AppBundle\Entity\Calendrier ca
            INNER JOIN AppBundle\Entity\Activite a WITH a.codact=ca.codb 
            WHERE ca.datdeb>=:start AND a.divact=:divact
            AND ca.langue != \'\''
        );
        $query->setParameters(['start' => new \DateTime(), 'divact' => 'RET']);
        $translationsFromCalendar = array_column($query->getResult(), 'langue');

        // Truc chelou pour récupérer les bonnes traductions à afficher
        $translationsForFilter = [];
        foreach ($translationsFromCalendar as $translation) {
            $translationsFromCalendar = explode(',', $translation);
            foreach ($translationsFromCalendar as $translationFromCalendar) {
                if (!in_array($translationFromCalendar, $translationsForFilter)) {
                    foreach ($existingTranslations as $existingTranslation) {
                        if ($existingTranslation['value'] === $translationFromCalendar) {
                            $translationsForFilter[$existingTranslation['value']] = $existingTranslation['name'];
                        }
                    }
                }
            }
        }

        $return = [];
        foreach ($translationsForFilter as $value => $availableTranslation) {
            $return[] = ['value' => $value, 'name' => $availableTranslation];
        }

        return $return;
    }

    public function findEventTypes()
    {
        $query = $this->repositoryTable->createQueryBuilder('t')
        ->select('t.tref as abbr', 't.tlib name', 't.tmemo color', 't.idt value')
        ->where('t.tlien = :tlien')
        ->orderBy('t.trang')
        ->setParameter(':tlien', 19);
        return $query->getQuery()->getResult();
    }
    
    public function findEvents()
    {
        $query = $this->entityManager->createQuery(
            'SELECT a.libact as event,
            c.codcal,
            a.codact AS actId,
            c.datdeb AS dateIn,
            c.datfin AS dateOut,
            a.sitact as site,
            t.tref as typeAbbr, t.tlib as typeName, t.tmemo as typeColor, t.idt AS typeValue, c.langue as translation, 
            (SELECT GROUP_CONCAT(CONCAT(co2.prenom, \' \' ,co2.nom, \' , \', co2.codco) SEPARATOR \'|\')
                FROM AppBundle\Entity\Contact co2 
                INNER JOIN AppBundle\Entity\CalL cal2 WITH co2.codco=cal2.lcal AND cal2.typlcal=:typlcal 
                INNER JOIN AppBundle\Entity\Calendrier ca2 WITH ca2.codcal=cal2.codcal 
                WHERE ca2.codcal=c.codcal) AS speakers 
             FROM AppBundle\Entity\Activite a 
            INNER JOIN AppBundle\Entity\Calendrier c WITH a.codact=c.codb 
            INNER JOIN AppBundle\Entity\Tables t WITH t.tref=a.typact 
            WHERE c.datdeb>=:start AND a.divact=:divact 
            ORDER BY c.datdeb'
        );
        $query->setParameters(['start' => new \DateTime(), 'divact' => 'RET', 'typlcal' => 'coAct']);
        return $query->getResult();
    }
    
    public function findRegisteredRefs($contactId)
    {
        $query = $this->entityManager->createQuery(
            'SELECT cal.reflcal
            FROM AppBundle\Entity\CalL cal
            WHERE cal.lcal=:contactId AND cal.typlcal=\'coIns\'
            ORDER BY cal.enreglcal DESC'
        );
        $query->setMaxResults(1);
        $query->setParameters(['contactId' => $contactId]);
        return $query->getResult();
    }
    
    public function findAttendees($registrationReferences, $contactId)
    {
        $query = $this->entityManager->createQuery(
            'SELECT DISTINCT co.nom, co.prenom, co.codco, co.ident, co.civil,
            co.civil2, co.adresse, co.cp, co.ville, co.pays, co.tel,
            co.mobil, co.email, co.profession, co.datnaiss, col.coltyp, col.colt, col.colp
            FROM AppBundle\Entity\Contact co
            LEFT JOIN AppBundle\Entity\ContactL col WITH co.codco=col.col
            JOIN AppBundle\Entity\CalL cal WITH co.codco=cal.lcal
            WHERE cal.reflcal IN (:registrationReferences) AND cal.lcal<>:contactId'
        );
        $query->setParameters(['registrationReferences' => $registrationReferences, 'contactId' => $contactId]);
        return $query->getResult();
    }
}
