<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 17.07.15
 * Time: 19:34
 */

namespace BugBundle\Tests;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\View\ChoiceView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntityTypeStub extends AbstractType
{
    /** @var ChoiceList */
    protected $choiceList = [];

    /** @var string */
    protected $name;

    protected $additionalOptions;

    /**
     * @param array $choices
     * @param string $name
     * @param array $additionalOptions
     */
    public function __construct(array $choices, $name = 'entity', $additionalOptions = array())
    {
        $this->additionalOptions = $additionalOptions;
        $this->name = $name;

        $keys = array_map('strval', array_keys($choices));
        $values = array_values($choices);

        $this->choiceList = new ChoiceList([], []);

        $keysReflection = new \ReflectionProperty(get_class($this->choiceList), 'values');
        $keysReflection->setAccessible(true);
        $keysReflection->setValue($this->choiceList, $keys);

        $valuesReflection = new \ReflectionProperty(get_class($this->choiceList), 'choices');
        $valuesReflection->setAccessible(true);
        $valuesReflection->setValue($this->choiceList, $values);

        $remainingViews = [];
        foreach ($choices as $key => $value) {
            $remainingViews[] = new ChoiceView($value, $key, $key);
        }

        $valuesReflection = new \ReflectionProperty(get_class($this->choiceList), 'remainingViews');
        $valuesReflection->setAccessible(true);
        $valuesReflection->setValue($this->choiceList, $remainingViews);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $options = ['choice_list' => $this->choiceList];
        $options=array_merge($options,$this->additionalOptions);
        $resolver->setDefaults($options);
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}