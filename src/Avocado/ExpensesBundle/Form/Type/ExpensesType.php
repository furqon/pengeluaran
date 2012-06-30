<?php

namespace Avocado\ExpensesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ExpensesType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('userid')
            ->add('time', 'datetime', array('input'=>'datetime', 'widget'=>'single_text'))
            ->add('notes')
            ->add('amount')
        ;
    }

    public function getName()
    {
        return 'expenses';
    }
}
