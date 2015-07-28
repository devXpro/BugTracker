<?php

namespace BugBundle\Controller;

use BugBundle\Entity\Issue;
use BugBundle\Entity\IssueComment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IssueCommentController extends Controller
{
    /**
     * @Route("/issue/comment/leave/{issue}/{issueComment}", name="issue_comment")
     * @ParamConverter("issueComment", class="BugBundle:IssueComment",
     * isOptional="true", options={"id" = "issueComment"})
     * @Security("has_role('ROLE_ADMIN') or is_granted('can_manipulate_comment_issue',issueComment)")
     * @param Request $request
     * @param Issue $issue
     * @param IssueComment|null $issueComment
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function leaveCommentAction(Request $request, Issue $issue, IssueComment $issueComment = null)
    {
        $em = $this->getDoctrine()->getManager();
        $issueComment = $issueComment ? $issueComment : new IssueComment();
        $form = $this->createForm('bug_issue_comment', $issueComment, array('issue' => $issue));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $issueComment = $form->getData();
            $em->persist($issueComment);
            $em->flush();
        }

        $issueComments = $em->getRepository('BugBundle:IssueComment')->findBy(array('issue' => $issue));

        return $this->render(
            '@Bug/IssueComment/issue_comment_view.html.twig',
            array('issueComments' => $issueComments, 'form' => $form->createView())
        );
    }

    /**
     * @Route("/issue/comment/delete/{issueComment}", name="issue_comment_delete")
     * @Security("has_role('ROLE_ADMIN') or is_granted('can_manipulate_comment_issue',issueComment)")
     * @param IssueComment $issueComment
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteCommentAction(IssueComment $issueComment)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($issueComment);
        $em->flush();
        $issue = $issueComment->getIssue();
        $form = $this->createForm('bug_issue_comment', $issueComment, array('issue' => $issue));
        $issueComments = $em->getRepository('BugBundle:IssueComment')->findBy(
            array('issue' => $issue)
        );

        return $this->render(
            '@Bug/IssueComment/issue_comment_view.html.twig',
            array('issueComments' => $issueComments, 'form' => $form->createView())
        );
    }
}
