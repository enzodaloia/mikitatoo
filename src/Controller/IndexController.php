<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Blog;
use App\Repository\BlogRepository;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(BlogRepository $blogRepository): Response
    {
        return $this->render('index/index.html.twig', [
            'blogs' => $blogRepository->findBy([], ['createdAt' => 'DESC'], 4),
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

    #[Route('/Miki-Kaneko', name: 'app_miki')]
    public function miki(): Response
    {
        return $this->render('team/miki.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/Elsa_Combes', name: 'app_elsa')]
    public function elsa(): Response
    {
        return $this->render('team/elsa.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/Jason-Colle', name: 'app_jason')]
    public function jason(): Response
    {
        return $this->render('team/jason.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/blog', name: 'app_blog')]
    public function blog(EntityManagerInterface $entityManager): Response
    {
        $blog = $entityManager->getRepository(Blog::class)->findAll();
        return $this->render('index/blog.html.twig', [
            'blog'=>$blog,
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
