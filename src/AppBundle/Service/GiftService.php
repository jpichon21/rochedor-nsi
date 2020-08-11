<?php

namespace AppBundle\Service;

use AppBundle\Entity\Don;
use AppBundle\Entity\DonR;
use AppBundle\Repository\DonRepository;
use AppBundle\Repository\DonRRepository;

class GiftService
{
    public const FREQUENCE_VIR_MONTHLY = 'M';
    public const FREQUENCE_VIR_TRIMESTRE = 'T';
    public const FREQUENCE_VIR_SEMESTRE = 'S';

    /**
     * @var DonRRepository
     */
    private $donRRepository;

    /**
     * @var DonRepository
     */
    private $donRepository;

    public function __construct(DonRRepository $donRRepository, DonRepository $donRepository)
    {
        $this->donRRepository = $donRRepository;
        $this->donRepository = $donRepository;
    }

    /**
     * Crée une nouvelle promesse de don
     *
     * @param array $data
     * @param $user
     *
     * @throws \Exception
     *
     * @return DonR
     */
    public function createDonR(array $data, $user)
    {
        $ref = $this->getNewRef(true);
        $virFreq = $this->getVirFrequence($data);
        $dateVir = !empty($data['dateDebVir']) ? new \DateTime($data['dateDebVir']) : new \DateTime('0000-00-00');
        $dateVirFin = !empty($data['dateFinVir']) ? new \DateTime($data['dateFinVir']) : new \DateTime('0000-00-00');

        $donR = new DonR();
        $donR->setMntdon($data['mntdon'])
            ->setContact($user)
            ->setDestdon($data['destdon'])
            ->setModdonr($data['moddon'])
            ->setMemodonR($data['memodon'])
            ->setRefdon($ref)
            ->setMondonR('€')
            ->setEnregdonR(new \DateTime())
            ->setBanqdon($this->getBankFromDestDon($data['destdon']))
            ->setDatVir($dateVir)
            ->setVirFin($dateVirFin)
            ->setVirFreq($virFreq);

        return $donR;
    }

    /**
     * Crée un nouveau don
     *
     * @param array $data
     * @param $user
     *
     * @throws \Exception
     *
     * @return Don
     */
    public function createDon(array $data, $user)
    {
        $ref = $this->getNewRef();
        $don = new Don();
        $don->setMntdon($data['mntdon'])
            ->setContact($user)
            ->setDestdon($data['destdon'])
            ->setModdon($data['moddon'])
            ->setMemodon($data['memodon'])
            ->setRefdon($ref)
            ->setEnregdon(new \DateTime())
            ->setDatdon(new \DateTime())
            ->setDatrecu(new \DateTime('0000-00-00 00:00:00'))
            ->setValidDon(0)
            ->setBanqdon($this->getBankFromDestDon($data['destdon']))
            ->setMondon('€');

        return $don;
    }

    /**
     * Retourne l'ID de compte correspondant à l'affectation du don
     *
     * @param string $destDon
     *
     * @return int
     */
    public function getBankFromDestDon($destDon)
    {
        switch ($destDon) {
            case 'Libre':
            case 'RochTx':
                return 9;
            case 'VieCom':
                return 9;
            case 'Itin':
                return 9;
            case 'FontTx':
                return 9;
            default:
                return 9;
        }
    }

    /**
     * Récupère la prochaine référence d'un don
     *
     * @param bool $isDonR
     *
     * @return string
     */
    private function getNewRef($isDonR = false)
    {
        $year = date('y');
        if ($isDonR) {
            $lastRef = $this->donRRepository->findLastRef($year);
        } else {
            $lastRef = $this->donRepository->findLastRef($year);
        }

        if ($lastRef === null) {
            return $year . '-0000';
        }

        return $year . '-' . str_pad(intval(str_replace($year . '-', '', $lastRef)) + 1, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Déduit la fréquence d'un virement régulier
     *
     * @param array $data
     *
     * @return string
     */
    private function getVirFrequence(array $data)
    {
        switch ($data['virPeriod']) {
            case 'select.period.monthly':
                $virFreq = self::FREQUENCE_VIR_MONTHLY;
                break;
            case 'select.period.trimestre':
                $virFreq = self::FREQUENCE_VIR_TRIMESTRE;
                break;
            case 'select.period.semestre':
                $virFreq = self::FREQUENCE_VIR_SEMESTRE;
                break;
            default:
                $virFreq = '';
        }

        return $virFreq;
    }
}
