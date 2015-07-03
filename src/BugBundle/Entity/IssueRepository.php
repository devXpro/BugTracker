<?php

namespace BugBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * IssueRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class IssueRepository extends EntityRepository
{
    public function getAllIssuesQuery(){
        return  $this->getEntityManager()->createQuery("SELECT i FROM BugBundle:issue i");
    }
}
