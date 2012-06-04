<?php

namespace Avocado\WelcomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('WelcomeBundle:Default:index.html.twig', array('name' => $name));
    }
}
