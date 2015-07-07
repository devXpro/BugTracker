<?php

namespace BugBundle\Controller;

use BugBundle\Entity\Issue;
use BugBundle\Entity\Project;
use BugBundle\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class IssueController extends Controller
{
    /**
     * @Route("/issues/list/{project}", name="issues_list", defaults={"project" = null})
     * @param Request $request
     * @param Project|null $project
     * @return Response
     */

    public function issuesListAction(Request $request,Project $project=null)
    {
        $em = $this->getDoctrine()->getManager();

        $issueRepository = $em->getRepository('BugBundle:Issue');
        $query = $this->isGranted('ROLE_ADMIN') ?
            $issueRepository->getAllIssuesQuery() :
            $issueRepository->getIssuesByUserQuery($this->getUser())->getResult();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),/*page number*/
            10 /*limit per page*/

        );
        return $this->render('@Bug/Issue/issues_list.html.twig', array('pagination' => $pagination));
    }

    /**
     * @Route("/issues/delete/{issue}", name="bug_issue_delete")
     * @param Request $request
     * @param issue $issue
     */
    public function issueDeleteAction(Request $request, Issue $issue){

    }

    /**
     * @Route("/issue/view/{issue}", name="bug_issue_view")
     * @param Request $request
     * @param Issue $issue
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function issueListAction(Request $request, Issue $issue){
        return $this->render('@Bug/Issue/issue_list.html.twig',array('issue'=>$issue));
    }

    /**
     * @Route("/issue/edit/{issue}", name="bug_issue_edit")
     * @param Request $request
     * @param Issue $issue
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function issueEditAction(Request $request, Issue $issue){
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('bug_issue', $issue);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $issue = $form->getData();
            $em->persist($issue);
            $em->flush();
            return $this->redirect($this->generateUrl('bug_issue_view',array('issue'=>$issue->getId())));
        }


        return $this->render('@Bug/Issue/issue_edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/issue/create", name="bug_issue_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function issueCreateAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        //If user is not admin and have not any projects he can't create Issue
        if (!$this->get('security.authorization_checker')->isGranted(Role::ROLE_ADMIN) &&
            count($em->getRepository('BugBundle:Project')->getProjectsByUser($this->getUser()))<1)
            return $this->render('@Bug/Messages/error.html.twig',array('message'=>$this->get('translator')->trans('youHaveNoAnyProjects')));


        $issue=new Issue();
        $form = $this->createForm('bug_issue', $issue);
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var Issue $issue */
            $issue = $form->getData();
            $em->persist($issue);
            $em->flush();
            return $this->redirect($this->generateUrl('bug_issue_view',array('issue'=>$issue->getId())));

        }


        return $this->render('@Bug/Issue/issue_edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
