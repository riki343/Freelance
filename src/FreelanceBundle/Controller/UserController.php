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

class UserController extends Controller
{
    /**
     * @Route("/user", name="freelance_userpage")
     * @return Response $response
     */
    public function indexAction() {
        return $this->render('FreelanceBundle::userpage.html.twig');
    }
}