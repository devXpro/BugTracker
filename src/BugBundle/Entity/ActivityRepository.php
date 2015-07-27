<?php

namespace BugBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * ActivityRepository
 */
class ActivityRepository extends EntityRepository
{

    /**
     * @param User $user
     * @return QueryBuilder
     */
    public function getActivitiesByUserQueryBuilder(User $user)
    {
        $qb = $this->createQueryBuilder('a')
            ->innerJoin('a.issue', 'i')
            ->innerJoin('i.project', 'p')
            ->innerJoin('p.members', 'members');
        $qb->where(
            $qb->expr()->in('members', ':user')
        )
            ->setParameter('user', $user);

        return $qb;
    }

    /**
     * @param User $user
     * @return Query
     */
    public function getActivitiesByUserQuery(User $user)
    {
        return $this->getActivitiesByUserQueryBuilder($user)->getQuery();
    }

    /**
     * @param User $user
     * @param Project $project
     * @return array
     */
    public function getActivitiesForProjectByUser(User $user, Project $project)
    {
        return $this->getActivitiesByUserQueryBuilder($user)
            ->andWhere('p = :project')
            ->setParameter('project', $project)
            ->getQuery()->getResult();
    }

    /**
     * @param User $user
     * @return array
     */
    public function getActivitiesByUser(User $user)
    {
        return $this->getActivitiesByUserQuery($user)->getResult();
    }
}
