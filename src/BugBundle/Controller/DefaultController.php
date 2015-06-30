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
        $user=$this->getUser();
        $name=$user->getUsername();
        return $this->render(':default:index.html.twig',array('name'=>$name));
    }
}
