<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241114143835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE approval (id SERIAL NOT NULL, by_id UUID NOT NULL, post_id UUID NOT NULL, when_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, changed_to INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_16E0952BAAE72004 ON approval (by_id)');
        $this->addSql('CREATE INDEX IDX_16E0952B4B89032C ON approval (post_id)');
        $this->addSql('COMMENT ON COLUMN approval.by_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN approval.post_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN approval.when_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE category (id UUID NOT NULL, title VARCHAR(60) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN category.id IS \'(DC2Type:ulid)\'');
        $this->addSql('CREATE TABLE category_post (category_id UUID NOT NULL, post_id UUID NOT NULL, PRIMARY KEY(category_id, post_id))');
        $this->addSql('CREATE INDEX IDX_D11116CA12469DE2 ON category_post (category_id)');
        $this->addSql('CREATE INDEX IDX_D11116CA4B89032C ON category_post (post_id)');
        $this->addSql('COMMENT ON COLUMN category_post.category_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN category_post.post_id IS \'(DC2Type:ulid)\'');
        $this->addSql('CREATE TABLE post (id UUID NOT NULL, post_type_id UUID NOT NULL, author_id UUID NOT NULL, title VARCHAR(60) NOT NULL, status INT DEFAULT 2 NOT NULL, content TEXT DEFAULT NULL, published_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, image_filename VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DF8A43BA0 ON post (post_type_id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DF675F31B ON post (author_id)');
        $this->addSql('COMMENT ON COLUMN post.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN post.post_type_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN post.author_id IS \'(DC2Type:ulid)\'');
        $this->addSql('CREATE TABLE post_type (id UUID NOT NULL, title VARCHAR(60) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN post_type.id IS \'(DC2Type:ulid)\'');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON "user" (username)');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE approval ADD CONSTRAINT FK_16E0952BAAE72004 FOREIGN KEY (by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE approval ADD CONSTRAINT FK_16E0952B4B89032C FOREIGN KEY (post_id) REFERENCES post (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category_post ADD CONSTRAINT FK_D11116CA12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category_post ADD CONSTRAINT FK_D11116CA4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF8A43BA0 FOREIGN KEY (post_type_id) REFERENCES post_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE approval DROP CONSTRAINT FK_16E0952BAAE72004');
        $this->addSql('ALTER TABLE approval DROP CONSTRAINT FK_16E0952B4B89032C');
        $this->addSql('ALTER TABLE category_post DROP CONSTRAINT FK_D11116CA12469DE2');
        $this->addSql('ALTER TABLE category_post DROP CONSTRAINT FK_D11116CA4B89032C');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8DF8A43BA0');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8DF675F31B');
        $this->addSql('DROP TABLE approval');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_post');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_type');
        $this->addSql('DROP TABLE "user"');
    }
}
