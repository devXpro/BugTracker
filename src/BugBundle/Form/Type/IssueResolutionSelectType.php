<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 30.06.15
 * Time: 16:39
 */

namespace BugBundle\Form\Type;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueResolutionSelectType extends AbstractType
{


    public function getName()
    {
        return 'bug_select_issue_resolution';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class' => 'BugBundle\Entity\IssueResolution',
        ));
    }

    public function getParent()
    {
        return 'entity';
    }
}