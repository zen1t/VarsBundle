<?php

namespace Zent\VarsBundle\Repository;

use Doctrine\ORM\EntityRepository;

class VarsRepository extends EntityRepository
{
    public function getVars()
    {
        return $this->createQueryBuilder('v')
            ->select('v.name', 'v.value')
            ->getQuery()->getArrayResult();
    }

    public function getVar($name)
    {
        $query = $this->createQueryBuilder('v')
            ->select('v.value')
            ->andWhere('v.name = :name')
            ->setParameter('name', $name);

        return $query->getQuery()->getOneOrNullResult();
    }

}