<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostcodeController extends AbstractController
{
    #[Route('/postcode', name: 'app_postcode')]
    public function index(): Response
    {
        return $this->render('postcode/index.html.twig', [
            'controller_name' => 'PostcodeController',
        ]);
    }
}
