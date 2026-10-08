<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/admin/blog')]
#[IsGranted('ROLE_ADMIN')]
final class BlogController extends AbstractController
{
    #[Route(name: 'app_blog_index', methods: ['GET'])]
    public function index(BlogRepository $blogRepository): Response
    {
        return $this->render('blog/index.html.twig', [
            'blogs' => $blogRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_blog_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $blog = new Blog();

        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile|null $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $newFilename = substr($blog->getToken(), 0, 32). '.' . $imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads/blog',
                    $newFilename
                );

                $blog->setImg($newFilename);
            }

            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute(
                'app_blog_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('blog/new.html.twig', [
            'blog' => $blog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blog_show', methods: ['GET'])]
    public function show(Blog $blog): Response
    {
        return $this->render('blog/show.html.twig', [
            'blog' => $blog,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_blog_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Blog $blog, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile|null $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $uploadDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads/blog';

                // Supprimer l'ancienne image
                if ($blog->getImg()) {
                    $oldImage = $uploadDirectory . DIRECTORY_SEPARATOR . $blog->getImg();

                    if (is_file($oldImage)) {
                        unlink($oldImage);
                    }
                }

                // Utiliser une partie du token comme nom de fichier
                $newFilename = substr($blog->getToken(), 0, 32)
                    . '.' . $imageFile->guessExtension();

                $imageFile->move(
                    $uploadDirectory,
                    $newFilename
                );

                $blog->setImg($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute(
                'app_blog_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('blog/edit.html.twig', [
            'blog' => $blog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blog_delete', methods: ['POST'])]
    public function delete(Request $request, Blog $blog, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blog->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($blog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_blog_index', [], Response::HTTP_SEE_OTHER);
    }
}
