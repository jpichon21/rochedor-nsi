<?php
namespace AppBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use AppBundle\Entity\User;

class UserRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, User::class);
    }

    /**
     * Check if username is unique
     *
     * @param string $username
     * @param int $id
     * @return boolean
     */
    public function isUsernameUnique($username, int $id = null)
    {
        $c = $this->findOneByUsername($username);
        if ($c === null) {
            return true;
        }
        if ($id === null) {
            return false;
        }
        if ($c->getId() !== intval($id)) {
            return false;
        }
        return true;
    }
}
