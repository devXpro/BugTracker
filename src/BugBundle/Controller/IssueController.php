<?php

namespace BugBundle\Controller;

use BugBundle\Entity\Issue;
use BugBundle\Entity\Project;
use BugBundle\Traits\ErrorVisualizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class IssueController extends Controller
{
    use ErrorVisualizer;

    /**
     * @Route("/issues/list/{project}", name="issues_list", defaults={"project" = null})
     * @param Request $request
     * @param Project|null $project
     * @return Response
     */

    public function issuesListAction(Request $request, Project $project = null)
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
     * @param Issue $issue
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function issueDeleteAction(Request $request, Issue $issue)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($issue);
        $em->flush();

        return $this->redirect($this->generateUrl('issues_list', array('issue' => $issue->getId())));
    }

    /**
     * @Route("/issue/view/{issue}/{onlyActivity}", name="bug_issue_view",defaults={"onlyActivity" = null})
     * @param Issue $issue
     * @return Response
     */
    public function issueListAction(Issue $issue, $onlyActivity = null)
    {
        $em = $this->getDoctrine()->getManager();
        $activities = $em->getRepository('BugBundle:Activity')->findBy(array('issue' => $issue));
        $template = $onlyActivity ? '@Bug/Issue/right_block.html.twig' : '@Bug/Issue/issue_list.html.twig';

        return $this->render($template, array('issue' => $issue, 'activities' => $activities));

    }


    /**
     * @Route("/issue/edit/{issue}", name="bug_issue_edit")
     * @param Request $request
     * @param Issue $issue
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function issueEditAction(Request $request, Issue $issue)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('bug_issue', $issue, array('parentIssue' => $issue->getParentIssue()));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $issue = $form->getData();
            $em->persist($issue);
            $em->flush();

            return $this->redirect($this->generateUrl('bug_issue_view', array('issue' => $issue->getId())));
        }


        return $this->render(
            '@Bug/Issue/issue_edit.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @Route("/issue/create/{parentIssue}", name="bug_issue_create")
     * @ParamConverter("parentIssue", class="BugBundle:Issue", isOptional="true", options={"id" = "parentIssue"})
     * @param Request $request
     * @param null|Issue $parentIssue
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function issueCreateAction(Request $request, Issue $parentIssue = null)
    {

        $em = $this->getDoctrine()->getManager();
        //If user is not admin and have not any projects he can't create Issue
        if (false === $this->isGranted('can_create_any_issue')) {
            return $this->renderError('youHaveNoAnyProjects');
        }
        if (false === $this->isGranted('can_create_children_issue', $parentIssue)) {
            return $this->renderError('onlyStoryCanHaveSubIssue');
        }
        $issue = new Issue();
        $form = $this->createForm('bug_issue', $issue, array('parentIssue' => $parentIssue));
        if ($request->isMethod('POST')) {


            $form->handleRequest($request);

            if ($form->isValid()) {
                $em->persist($issue);
                $em->flush();

                return $this->redirect($this->generateUrl('bug_issue_view', array('issue' => $issue->getId())));

            }
        }

        return $this->render(
            '@Bug/Issue/issue_edit.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
}
