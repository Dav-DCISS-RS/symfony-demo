<?php

namespace Kitpages\UserGeneratedBundle\Repository;

use Doctrine\ORM\EntityRepository;

use Kitpages\UserGeneratedBundle\Entity\RatingCache;

/**
 * RatingResultRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RatingCacheRepository extends EntityRepository
{
    public function findByItemReferenceAndScore($itemReference, $scoreType, $score)
    {
        $dql = "
            SELECT rc
            FROM KitpagesUserGeneratedBundle:RatingCache rc
            WHERE rc.itemReference = :itemReference
            AND  rc.score = :score
            AND rc.scoreType = :scoreType
        ";

         $query = $this->_em
            ->createQuery($dql)
            ->setParameter("itemReference", $itemReference)
            ->setParameter("score", $score)
            ->setParameter("scoreType", $scoreType);

        return $query->getResult();
    }

    public function quantityTotalForItemReferenceAndScoreType($itemReference, $scoreType)
    {
        $dql = "
            SELECT rc.quantityTotal as quantity
            FROM KitpagesUserGeneratedBundle:RatingCache rc
            WHERE rc.itemReference = :itemReference
            AND rc.scoreType = :scoreType
            GROUP BY rc.itemReference
        ";
        $query = $this->_em
           ->createQuery($dql)
           ->setParameter("itemReference", $itemReference)
           ->setParameter("scoreType", $scoreType);
        $total = $query->getResult();

        if ($total != null ) {
            return $total[0]['quantity'];
        } else {
            return 0;
        }
    }

    public function quantityByScore($itemReference, $scoreType){
        $dql = "
            SELECT rc.quantity as quantity, rc.score as score
            FROM KitpagesUserGeneratedBundle:RatingCache rc
            WHERE rc.itemReference = :itemReference
            AND rc.scoreType = :scoreType
            GROUP BY rc.score
        ";

         $query = $this->_em
            ->createQuery($dql)
            ->setParameter("itemReference", $itemReference)
            ->setParameter("scoreType", $scoreType);
        return $query->getResult();
    }


    public function percentageByScore($itemReference, $scoreType)
    {
        $dql = "
            SELECT 100*rc.quantity/rc.quantityTotal as percentage, rc.score as score
            FROM KitpagesUserGeneratedBundle:RatingCache rc
            WHERE rc.itemReference = :itemReference
            AND rc.scoreType = :scoreType
            GROUP BY rc.score
        ";

        $query = $this->_em
            ->createQuery($dql)
            ->setParameter("itemReference", $itemReference)
            ->setParameter("scoreType", $scoreType);

        return $query->getResult();

    }

    public function average($itemReference, $scoreType)
    {
        $dql = "
            SELECT rc.scoreTotal/rc.quantityTotal as average
            FROM KitpagesUserGeneratedBundle:RatingCache rc
            WHERE rc.itemReference = :itemReference
            AND rc.scoreType = :scoreType
        ";

         $query = $this->_em
            ->createQuery($dql)
            ->setParameter("itemReference", $itemReference)
            ->setParameter("scoreType", $scoreType)
            ->setMaxResults(1);

        $average = $query->getResult();
        if ($average != null ) {
            return $average[0]['average'];
        } else {
            return null;
        }
    }

}