<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 30.06.15
 * Time: 16:39
 */

namespace BugBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParentIssueSetType extends AbstractType
{


    public function getName()
    {
        return 'bug_set_parent_issue';
    }

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults(
            array(
                'class' => 'BugBundle\Entity\Issue',
            )
        );
    }

    public function getParent()
    {
        return 'entity';
    }
}