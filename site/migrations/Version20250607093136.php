<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250607093136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE "cash_accounts" (id UUID NOT NULL, company_id UUID NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(50) NOT NULL, opening_balance DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A84B6F61979B1AD6 ON "cash_accounts" (company_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "cash_accounts" ADD CONSTRAINT FK_A84B6F61979B1AD6 FOREIGN KEY (company_id) REFERENCES "companies" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "cash_accounts" DROP CONSTRAINT FK_A84B6F61979B1AD6
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "cash_accounts"
        SQL);
    }
}
