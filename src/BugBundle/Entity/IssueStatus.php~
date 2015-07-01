<?php

namespace BugBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IssueStatus
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class IssueStatus
{
    public function __toString(){
        return $this->getLable();
    }
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="lable", type="string", length=255)
     */
    private $lable;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lable
     *
     * @param string $lable
     * @return IssueStatus
     */
    public function setLable($lable)
    {
        $this->lable = $lable;

        return $this;
    }

    /**
     * Get lable
     *
     * @return string 
     */
    public function getLable()
    {
        return $this->lable;
    }
}
