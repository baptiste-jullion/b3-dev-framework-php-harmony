<?php

namespace App\DataFixtures;


use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;


class Comments extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly string $projectDir)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $dataYamlLink = $this->projectDir . '/fixtures/data/comments.yaml';
        $data = Yaml::parseFile($dataYamlLink)["comments"];

        foreach ($data as $commentData) {
            $comment = new Comment();
            $comment->setContent($commentData["content"]);
            $comment->setSentAt(new \DateTimeImmutable($commentData["sent_at"]));
            $comment->setAuthor($manager->getRepository(User::class)->findOneBy(["email" => $commentData["author"]]));
            $comment->setArticle($manager->getRepository(Article::class)->findOneBy(["slug" => $commentData["article"]]));
            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            Users::class,
            Articles::class,
        ];
    }
}
