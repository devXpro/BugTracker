<?php

namespace BugBundle\Controller;

use BugBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class UserController extends Controller
{
    /**
     * @Route("/profile/edit/",name="user_profile_edit")
     * @param Request $request
     *
     */

    public function profileEditAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user=$this->getUser();
        $form = $this->createForm('bug_user_profile', $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $form->getData();
            $user = $this->container->get('bug.userManager')->encodePassword($user);
            $user->upload();
            $em->persist($user);
            $em->flush();
            return $this->redirect($this->generateUrl('index'));
        }


        return $this->render('@Bug/User/user_profile_edit.html.twig', array(
            'form' => $form->createView(),
        ));

    }
}
