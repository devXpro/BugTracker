<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 07.07.15
 * Time: 18:59
 */

namespace BugBundle\Command;


use BugBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PeriodicNotificationCommand extends ContainerAwareCommand
{
    /**
     *
     */
    protected function configure()
    {
        $this->setName('bug:collaborators:notify')
            ->setDescription('Periodic notify all collaborators about all activities');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $unNonifiedEvents = $em->getRepository('BugBundle:Activity')->findBy(array('notified' => false));
        if ($unNonifiedEvents) {
            foreach ($unNonifiedEvents as $activity) {
                if ($activity->getIssue()) {
                    $collaborators = $activity->getIssue()->getCollaborators();
                    $subj = $this->getContainer()->get('bug.activity.manager')->getTypeName($activity->getType());
                    $body = $activity->getMessage();
                    foreach ($collaborators as $collaborator) {
                        /** @var User $collaborator */
                        /** @var \Swift_Message $message */
                        $message = \Swift_Message::newInstance()
                            ->setSubject($subj)
                            ->setFrom('test@test.com')
                            ->setTo($collaborator->getEmail())
                            ->setBody($body);
                        $this->getContainer()->get('mailer')->send($message);
                        $output->writeln('<comment>User:' . $collaborator . ' was notified </comment>');
                    }
                }
            }
        }
    }
}