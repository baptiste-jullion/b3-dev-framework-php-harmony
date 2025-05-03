<?php

namespace App\Controller;


use App\Entity\Article;
use App\Form\ArticleForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


final class ArticleController extends AbstractController
{
    #[Route('/blog', name: 'app_article_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findAllPublished();

        return $this->render('article/list.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/blog/{slug}', name: 'app_article_by_slug')]
    public function show(EntityManagerInterface $entityManager, string $slug): Response
    {
        $article = $entityManager->getRepository(Article::class)->findOneBy(["slug" => strtolower($slug)]);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        return $this->render('article/slug.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/blog/{id}/edit', name: 'app_article_edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, EntityManagerInterface $entityManager, string $id): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        $form = $this->createForm(ArticleForm::class, $article);
        $form->handleRequest($request);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();
            $this->addFlash('success', 'Article updated successfully.');

            return $this->redirectToRoute('app_article_by_slug', ['slug' => $article->getSlug()]);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/blog/{id}/delete', name: 'app_article_delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(EntityManagerInterface $entityManager, string $id): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        $entityManager->remove($article);
        $entityManager->flush();
        $this->addFlash('success', 'Article supprimé.');

        return $this->redirectToRoute('app_article_list');
    }
}
