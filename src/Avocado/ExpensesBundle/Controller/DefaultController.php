<?php

namespace Avocado\ExpensesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('ExpensesBundle:Default:index.html.twig', array('name' => $name));
    }
}
