<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 30.06.15
 * Time: 16:39
 */

namespace BugBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssuePrioritySelectType extends AbstractType
{


    public function getName()
    {
        return 'bug_select_issue_priority';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class' => 'BugBundle\Entity\IssuePriority',
        ));
    }

    public function getParent()
    {
        return 'entity';
    }
}