<?php

namespace BugBundle\Controller;

use BugBundle\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ProjectController extends Controller
{
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
            $request->query->getInt('page', 1),/*page number*/
            5 /*limit per page*/

        );
        return $this->render('@Bug/Project/projects_list.html.twig', array('pagination' => $pagination));
    }

    /**
     * @Route("/projects/delete/{project}", name="bug_project_delete")
     * @param Request $request
     * @param Project $project
     */
    public function projectDeleteAction(Request $request, Project $project){

    }

    /**
     * @Route("/project/view/{project}", name="bug_project_view")
     * @param Request $request
     * @param Project $project
     */
    public function projectListAction(Request $request, Project $project){
        return $this->render('@Bug/Project/project_list.html.twig',array('project'=>$project));
    }

    /**
     * @Route("/project/edit/{project}", name="bug_project_edit")
     * @param Request $request
     * @param Project $project
     */
    public function projectEditAction(Request $request, Project $project){
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('bug_project', $project);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $project = $form->getData();
            $em->persist($project);
            $em->flush();
            return $this->redirect($this->generateUrl('bug_project_view',array('project'=>$project->getId())));
        }


        return $this->render('@Bug/Project/project_edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/project/create", name="bug_project_create")
     * @param Request $request

     */
    public function projectCreateAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $project=new Project();
        $project->setCreator($this->getUser());
        $form = $this->createForm('bug_project', $project);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $project = $form->getData();
            $em->persist($project);
            $em->flush();
            return $this->redirect($this->generateUrl('bug_project_view',array('project'=>$project->getId())));
        }


        return $this->render('@Bug/Project/project_edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
