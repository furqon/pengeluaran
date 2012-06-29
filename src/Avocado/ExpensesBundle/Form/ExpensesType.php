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
            ->add('time', 'datetime', array('input'=>'datetime', 'widget'=>'single_text'))
            ->add('notes')
            ->add('amount')
        ;
    }

    public function getName()
    {
        return 'frm';
    }
}
