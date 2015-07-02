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

class ProjectType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('label','text')
            ->add('summary','textarea')
            ->add('code','text')
            ->add('members','entity',array(
                'class'=>'BugBundle\Entity\User',
                'property'=>'username',
                'multiple'=>true
            ))
        ;
    }
    public function getName(){
        return 'bug_project';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BugBundle\Entity\Project',
        ));
    }
}