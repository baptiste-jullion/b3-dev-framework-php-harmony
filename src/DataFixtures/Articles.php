<?php

namespace App\DataFixtures;


use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Yaml\Yaml;


class Articles extends Fixture
{
    public function __construct(private readonly string $projectDir)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $slugger = new AsciiSlugger();

        $dataYamlLink = $this->projectDir . '/fixtures/data/articles.yaml';
        $data = Yaml::parseFile($dataYamlLink)["articles"];

        foreach ($data as $articleData) {
            $article = new Article();
            $article->setTitle($articleData["title"]);
            $article->setSlug($slugger->slug($articleData["title"]));
            $article->setContent($articleData["content"]);
            $manager->persist($article);
        }

        $manager->flush();
    }
}
