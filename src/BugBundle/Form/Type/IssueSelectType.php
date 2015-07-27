<?php

namespace BugBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueSelectType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'bug_select_issue';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'class' => 'BugBundle\Entity\Issue',
            )
        );
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'entity';
    }
}
