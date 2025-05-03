<?php

namespace App\DataFixtures;


use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Yaml\Yaml;


class Articles extends Fixture implements DependentFixtureInterface
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
            $article->setSlug($slugger->slug($articleData["title"])->lower());
            $article->setContent($articleData["content"]);
            $article->setCover(($articleData["cover"] ?? null) ? Uuid::fromString($articleData["cover"]) : null);
            $article->setAuthor($manager->getRepository(User::class)->findOneBy(["email" => $articleData["author"]]));
            $article->setPublishedAt(new \DateTimeImmutable($articleData["published_at"]));
            $manager->persist($article);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            Users::class,
        ];
    }
}
