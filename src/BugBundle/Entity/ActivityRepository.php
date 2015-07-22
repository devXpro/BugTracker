<?php

namespace BugBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ActivityRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ActivityRepository extends EntityRepository
{

    public function getActivitiesByUserQuery(User $user)
    {
        $qb = $this->createQueryBuilder('a')
            ->innerJoin('a.issue', 'i')
            ->innerJoin('i.project', 'p')
            ->leftJoin('p.members', 'members');
        $qb->where(
            $qb->expr()->orX(
                $qb->expr()->in('members', ':user'),
                $qb->expr()->eq('p.creator', ':user')
            )
        )
            ->setParameter('user', $user);

        return $qb->getQuery();
    }

    public function getActivitiesByUser(User $user)
    {
        return $this->getActivitiesByUserQuery($user)->getResult();
    }
}
