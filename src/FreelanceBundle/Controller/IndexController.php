<?php

namespace FreelanceBundle\Controller;

use Doctrine\ORM\EntityManager;
use FreelanceBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    /**
     * @Route("/", name="freelance_homepage")
     * @return Response $response
     */
    public function indexAction() {
        return $this->render('FreelanceBundle::home.html.twig');
    }

    /**
     * @Route("/signup", name="freelance_signup")
     * @return Response $response
     */
    public function signupAction() {
        return $this->render('FreelanceBundle::signup.html.twig');
    }

    /**
     * @Route("/signup_action", name="freelance_signup_action")
     * @param Request $request
     * @return RedirectResponse
     */
    public function signupActionAction(Request $request)
    {
        $username = $request->request->get('username');
        $exists = $this->getDoctrine()->getRepository('FreelanceBundle:User')->findOneByUsername($username);
        if ($exists != null) {
            return $this->render('FreelanceBundle::signup.html.twig', array(
                'zm' => "Пользователь с данным логином уже зарегестрирован"
            ));
        }

        $password = $request->request->get('password');
        $repeatPassword = $request->request->get('repeatPassword');
        if ($password != $repeatPassword)
            return $this->render('FreelanceBundle::signup.html.twig', array(
                'zm' => "Пароли не совпадают"
            ));

        $email = $request->request->get('email');
        $name = $request->request->get('name');
        $surname = $request->request->get('surname');
        $role = $request->request->get('role');

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $parameters = array(
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'name' => $name,
            'surname' => $surname,
            'role' => $role,
        );

        User::addUser($em, $this->get('security.encoder_factory'), $parameters);
        return $this->redirectToRoute('freelance_login');
    }

    /**
     * @Route("/login", name="freelance_login")
     * @return Response $response
     */
    public function loginAction() {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@Freelance/login.html.twig',
            array(
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction() {

    }
}
