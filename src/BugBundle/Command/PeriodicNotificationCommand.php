<?php

namespace BugBundle\Command;

use BugBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PeriodicNotificationCommand extends ContainerAwareCommand
{
    const RECORD_LIMIT = 500;

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
        $output->writeln('<info>Start</info>');
        $unNonifiedEvents = $em->getRepository('BugBundle:Activity')->findBy(array('notified' => false));
        $mailer = $this->getContainer()->get('mailer');
        if ($unNonifiedEvents) {
            foreach ($unNonifiedEvents as $activity) {
                if ($activity->getIssue()) {
                    $collaborators = $activity->getIssue()->getCollaborators();
                    $subj = $this->getContainer()->get('bug.activity.manager')->getTypeName($activity->getType());
                    $body = $this->getContainer()->get('twig')->render(
                        '@Bug/Issue/activity.html.twig',
                        array('activities' => array($activity))
                    );
                    $limit = self::RECORD_LIMIT;
                    foreach ($collaborators as $collaborator) {
                        /** @var User $collaborator */
                        /** @var \Swift_Message $message */
                        $message = \Swift_Message::newInstance()
                            ->setSubject($subj)
                            ->setFrom('test@test.com')
                            ->setTo($collaborator->getEmail())
                            ->setBody($body);
                        //Uncomment after send Email
                        try {
                            $mailer->send($message);
                        } catch (Exception $e) {
                            $format = '<error>User: %u was not notified, because: %e</error>';
                            $output->writeln(
                                sprintf($format, $collaborator, $e->getMessage())
                            );
                        }
                        $format = '<comment>User: %u was notified </comment>';
                        $output->writeln(
                            sprintf($format, $collaborator)
                        );
                        $activity->setNotified(true);
                        $em->persist($activity);
                        if ($limit <= 0) {
                            $em->flush();
                            $limit = self::RECORD_LIMIT;
                        }
                        $limit++;
                    }

                    $em->flush();
                }
            }
        } else {
            $output->writeln('<info>Nothing to send </info>');
        }

    }
}
