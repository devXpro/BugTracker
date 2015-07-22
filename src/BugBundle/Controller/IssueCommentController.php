<?php

namespace BugBundle\Controller;

use BugBundle\Entity\Issue;
use BugBundle\Entity\IssueComment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IssueCommentController extends Controller
{
    /**
     * @Route("/issue/comment/{issue}", name="issue_comment")
     * @param Request $request
     * @param Issue $issue
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, Issue $issue)
    {
        $em = $this->getDoctrine()->getManager();
        $issueComment = new IssueComment();
        $form = $this->createForm('bug_issue_comment', $issueComment, array('issue' => $issue));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $issueComment = $form->getData();
            $em->persist($issueComment);
            $em->flush();

            return $this->redirect($this->generateUrl('bug_issue_view', array('issue' => $issue->getId())));
        }

        $issueComments = $em->getRepository('BugBundle:IssueComment')->findBy(array('issue' => $issue));

        return $this->render(
            '@Bug/IssueComment/issue_comment_view.html.twig',
            array('issueComments' => $issueComments, 'form' => $form->createView())
        );
    }
}
