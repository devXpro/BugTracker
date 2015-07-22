<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 30.06.15
 * Time: 16:39
 */

namespace BugBundle\Form\Type;


use BugBundle\Entity\Issue;
use BugBundle\Services\TransHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueTypeSelectType extends AbstractType
{

    private $trans;

    public function __construct(TransHelper $trans)
    {
        $this->trans = $trans;
    }

    public function getName()
    {
        return 'bug_select_issue_type';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'choices' => array(
                    Issue::TYPE_BUG => $this->trans->transUp('bug'),
                    Issue::TYPE_STORY => $this->trans->transUp('story'),
                    Issue::TYPE_SUBTASK => $this->trans->transUp('subtask'),
                    Issue::TYPE_TASK => $this->trans->transUp('task'),
                ),
            )
        );

    }

    public function getParent()
    {
        return 'choice';
    }
}