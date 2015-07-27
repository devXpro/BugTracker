<?php

namespace BugBundle\Form\Type;

use BugBundle\Entity\Issue;
use BugBundle\Services\TransHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueTypeSelectType extends AbstractType
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
        return 'bug_select_issue_type';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'choices' => array(
                    Issue::TYPE_BUG => $this->trans->transUp('bug'),
                    Issue::TYPE_STORY => $this->trans->transUp('story'),
                    Issue::TYPE_TASK => $this->trans->transUp('task'),
                ),
            )
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
