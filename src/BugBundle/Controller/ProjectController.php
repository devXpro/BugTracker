<?php

namespace BugBundle\Controller;

use BugBundle\Entity\Project;
use BugBundle\Entity\Role;
use BugBundle\Traits\ErrorVisualizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProjectController extends Controller
{
    use ErrorVisualizer;

    /**
     * @Route("/projects/list/", name="projects_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function projectsListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $projectRepository = $em->getRepository('BugBundle:Project');
        $query = $this->isGranted('ROLE_ADMIN') ?
            $projectRepository->getAllProjectsQuery() :
            $projectRepository->getProjectsByUserQuery($this->getUser())->getResult();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('@Bug/Project/projects_list.html.twig', array('pagination' => $pagination));
    }

    /**
     * @Route("/project/delete/{project}", name="bug_project_delete")
     * @Security("has_role('ROLE_ADMIN') or is_granted('can_manipulate_project',project)")
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function projectDeleteAction(Request $request, Project $project)
    {
        return new Response('delete is not need');
    }

    /**
     * @Route("/project/view/{project}", name="bug_project_view")
     * @Security("has_role('ROLE_ADMIN') or is_granted('can_manipulate_project',project)")
     * @param Request $request
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function projectViewAction(Request $request, Project $project)
    {
        return $this->render('@Bug/Project/project_list.html.twig', array('project' => $project));
    }

    /**
     * @Route("/project/edit/{project}", name="bug_project_edit")
     * @Security("has_role('ROLE_ADMIN') or is_granted('can_manipulate_project',project)")
     * @param Request $request
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function projectEditAction(Request $request, Project $project)
    {
        if (!$this->get('security.authorization_checker')->isGranted(Role::ROLE_MANAGER)) {
            return $this->renderError('notEnoughPermissions');
        }
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('bug_project', $project);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $project = $form->getData();
            $em->persist($project);

            $em->flush();

            return $this->redirect($this->generateUrl('bug_project_view', array('project' => $project->getId())));
        }


        return $this->render(
            '@Bug/Project/project_edit.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @Route("/project/create", name="bug_project_create")
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_MANAGER')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function projectCreateAction(Request $request)
    {
        if (!$this->isGranted(Role::ROLE_MANAGER)) {
            return $this->renderError('notEnoughPermissions');
        }
        $em = $this->getDoctrine()->getManager();
        $project = new Project();
        $form = $this->createForm('bug_project', $project);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $project = $form->getData();
            $em->persist($project);
            $em->flush();

            return $this->redirect($this->generateUrl('bug_project_view', array('project' => $project->getId())));
        }


        return $this->render(
            '@Bug/Project/project_edit.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

}
