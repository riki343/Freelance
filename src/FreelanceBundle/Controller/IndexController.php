<?php

namespace FreelanceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    public function indexAction() {
        return $this->render('FreelanceBundle::home.html.twig');
    }
}
