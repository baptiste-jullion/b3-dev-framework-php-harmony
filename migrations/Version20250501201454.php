<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250501201454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE article DROP CONSTRAINT fk_23a0e66f675f31b
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_23a0e66f675f31b
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article DROP author_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article ALTER created_at DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP CONSTRAINT fk_9474526cf675f31b
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP CONSTRAINT fk_9474526c7294869c
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_9474526c7294869c
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_9474526cf675f31b
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP author_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP article_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD author_id UUID NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD article_id UUID NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN comment.author_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN comment.article_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD CONSTRAINT fk_9474526cf675f31b FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD CONSTRAINT fk_9474526c7294869c FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_9474526c7294869c ON comment (article_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_9474526cf675f31b ON comment (author_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article ADD author_id UUID NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article ALTER created_at SET NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN article.author_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article ADD CONSTRAINT fk_23a0e66f675f31b FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_23a0e66f675f31b ON article (author_id)
        SQL);
    }
}
