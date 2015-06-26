<?php

namespace BugBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {

        return $this->render('BugBundle:Default:index.html.twig');
    }
}
