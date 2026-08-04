<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/prestation', name: 'app_prestation')]
    public function prestation(): Response
    {
        return $this->render('index/prestation.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/qui', name: 'app_who')]
    public function who(): Response
    {
        return $this->render('index/who.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/portfolio', name: 'app_portfolio')]
    public function team(): Response
    {
        return $this->render('index/portfolio.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/process', name: 'app_process')]
    public function process(): Response
    {
        return $this->render('index/process.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/miki-kaneko', name: 'app_miki')]
    public function miki(): Response
    {
        return $this->render('team/miki.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/jason-colle', name: 'app_jason')]
    public function jason(): Response
    {
        return $this->render('team/jason.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/blog', name: 'app_blog')]
    public function blog(): Response
    {
        return $this->render('index/blog.html.twig', [
        ]);
    }

    #[Route('/post', name: 'app_post')]
    public function post(): Response
    {
        return $this->render('index/post.html.twig', [
        ]);
    }

    #[Route('/coming-soon', name: 'app_coming_soon')]
    public function comingSoon(): Response
    {
        return $this->render('index/coming.html.twig', [
        ]);
    }
}
