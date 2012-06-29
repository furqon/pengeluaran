<?php

namespace Avocado\WelcomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Avocado\UserBundle\Entity\User;


class DefaultController extends Controller
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }

    public function indexAction()
    {
        return $this->render('WelcomeBundle:Default:index.html.twig');
    }
}