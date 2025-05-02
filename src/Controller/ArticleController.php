<?php

namespace App\Controller;


use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class ArticleController extends AbstractController
{
    #[Route('/blog', name: 'app_article_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findAll();

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
}
