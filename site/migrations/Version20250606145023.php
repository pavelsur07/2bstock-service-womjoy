<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250606145023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE "companies" (id UUID NOT NULL, name VARCHAR(60) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "companies".created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE company_user (company_id UUID NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(company_id, user_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CEFECCA7979B1AD6 ON company_user (company_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CEFECCA7A76ED395 ON company_user (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE company_user ADD CONSTRAINT FK_CEFECCA7979B1AD6 FOREIGN KEY (company_id) REFERENCES "companies" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE company_user ADD CONSTRAINT FK_CEFECCA7A76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE company_user DROP CONSTRAINT FK_CEFECCA7979B1AD6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE company_user DROP CONSTRAINT FK_CEFECCA7A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "companies"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE company_user
        SQL);
    }
}
