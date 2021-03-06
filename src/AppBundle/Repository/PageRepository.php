<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Page;

/**
 * PageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PageRepository extends \Doctrine\ORM\EntityRepository
{
    public function findGoodTranslation($id, $locale)
    {
        
        $page = $this->find($id);
        $parent = $page->getParent();
        if ($locale === 'fr') {
            return $parent;
        }

        if ($parent === null) {
            return null;
        }

        $children = $parent->getChildren();
        if ($children === null) {
            return null;
        }

        $arrayOfChild = $children->getValues();
        foreach ($arrayOfChild as $childPage) {
            if ($childPage->getLocale() === $locale) {
                return $childPage;
            }
        }

        return null;
    }

    public function findByLocale($locale)
    {
        return $this->getEntityManager()->getRepository(Page::class)
            ->createQueryBuilder('p')
            ->where('p.locale = :locale')
            ->setParameter('locale', $locale)
            ->getQuery()->execute();
    }
}
