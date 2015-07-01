<?php

namespace BugBundle\Controller;

use BugBundle\Entity\User;
use BugBundle\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * @Route("/admin/users/list", name="admin_users_list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function usersListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT u FROM BugBundle:USER u";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),/*page number*/
            5 /*limit per page*/

        );
        return $this->render('@Bug/Admin/Users/users_list.html.twig', array('pagination' => $pagination));
    }

    /**
     * @Route("/admin/users/edit/{user}")
     * @param Request $request
     * @param User $user
     */
    public function userEditAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('bug_user', $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $form->getData();
            //$user = $this->container->get('bug.userManager')->encodePassword($user);
            $user->upload();
            $em->persist($user);
            $em->flush();
            return $this->redirect('/admin/users/list');
        }


        return $this->render('@Bug/Admin/Users/user_edit.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/admin/users/delete/{user}")
     * @param Request $request
     * @param User $user
     */
    public function userDeleteAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirect('/admin/users/list');

    }


}
