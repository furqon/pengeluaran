<?php

namespace Avocado\ExpensesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ExpensesType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('userid')
            ->add('time')
            ->add('record_id')
            ->add('notes')
            ->add('amount')
        ;
    }

    public function getName()
    {
        return 'avocado_expensesbundle_expensestype';
    }
}
