<?php

namespace BugBundle\Controller;


use BugBundle\Entity\Role;
use BugBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraint;


class AuthController extends Controller
{

    /**
     * @Route("/login", name="login_route")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            '@Bug/Auth/login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error' => $error,
            )
        );
    }

    /**
     * @Route("/registration", name="registration_route")
     */
    public function registrationAction(Request $request)
    {
        $user = new User();

        $form = $this->createFormBuilder(
            $user,
            array('validation_groups' => array('registration', Constraint::DEFAULT_GROUP))
        )
            ->add('email', 'email')
            ->add('username', 'text')
            ->add('password', 'password')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $x = $this->get('validator')->validate($user);
            $em = $this->getDoctrine()->getManager();
            /** @var $user User $user */
            $user = $form->getData();
            $user = $this->container->get('bug.userManager')->encodePassword($user);
            $roleUser = $em->getRepository('BugBundle:Role')->findOneBy(array('role' => Role::ROLE_USER));
            $user->addRole($roleUser);
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('login_route'));
        }

        return $this->render(
            '@Bug/Auth/register.html.twig',
            array(
                'form' => $form->createView(),
            )
        );

    }
}
