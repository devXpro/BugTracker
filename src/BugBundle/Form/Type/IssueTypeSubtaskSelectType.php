<?php

namespace BugBundle\Form\Type;

use BugBundle\Entity\Issue;
use BugBundle\Services\TransHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueTypeSubtaskSelectType extends AbstractType
{
    /** @var TransHelper */
    private $trans;

    /**
     * @param TransHelper $trans
     */
    public function __construct(TransHelper $trans)
    {
        $this->trans = $trans;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bug_select_issue_type_subtask';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array('choices' => array(Issue::TYPE_SUBTASK => $this->trans->transUp('subtask')), 'read_only' => true)
        );

    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice';
    }
}
