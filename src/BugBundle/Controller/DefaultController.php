<?php

namespace BugBundle\Controller;

use BugBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        /** @var  $user User*/
        if($user=$this->getUser()) {
            return $this->render('@Bug/base.html.twig');
        } else
            return $this->redirect($this->generateUrl('login_route'));
    }
}
