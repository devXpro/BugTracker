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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function profileEditAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm('bug_user_profile', $user, array('validation_groups' => array('edit_profile')));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $newPassword = $form->get('plainPassword')->get('first')->getData();
            if ($newPassword) {
                $this->container->get('bug.userManager')->encodePassword($user, $newPassword);
            }
            $user->upload();
            $em->flush();

            return $this->redirect($this->generateUrl('index'));
        }

        return $this->render(
            '@Bug/User/user_profile_edit.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @Route("/user/{user}",name="user_page",defaults={"user"=null})
     * @param Request $request
     * @param null $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userPage(Request $request, $user = null)
    {
        $em = $this->getDoctrine()->getManager();
        $userEntity = $user ? $em->getRepository('BugBundle:User')->find($user) : $this->getUser();
        $activities = $em->getRepository('BugBundle:Activity')->getActivitiesByUser($userEntity);
        $issuesQuery = $em->getRepository('BugBundle:Issue')->getActualIssuesByUserCollaboratorQuery($userEntity);
        $paginator = $this->get('knp_paginator');
        $issuesPagination = $paginator->paginate(
            $issuesQuery,
            $request->query->getInt('page', 1),
            10
        );
        $params = array('activities' => $activities, 'pagination' => $issuesPagination);
        if ($user) {
            $params['user'] = $userEntity;
        }

        return $this->render('@Bug/User/user_page.html.twig', $params);
    }
}
