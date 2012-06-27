<?php

namespace Avocado\WelcomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Avocado\ExpensesBundle\Entity\Expenses;
use Avocado\ExpensesBundle\Form\ExpensesType;


class DashboardController extends Controller
{
    
    public function indexAction()
    {
        $entity = new Expenses();
        $form   = $this->createForm(new ExpensesType(), $entity);

        return $this->render('WelcomeBundle:Default:dashboard.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));

        return $this->render('WelcomeBundle:Default:dashboard.html.twig');
    }
}