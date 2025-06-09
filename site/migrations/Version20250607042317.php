<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250607042317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE "cash_flow_categories" (id UUID NOT NULL, parent_id UUID DEFAULT NULL, company_id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4E3C2D23727ACA70 ON "cash_flow_categories" (parent_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4E3C2D23979B1AD6 ON "cash_flow_categories" (company_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "cash_flow_categories" ADD CONSTRAINT FK_4E3C2D23727ACA70 FOREIGN KEY (parent_id) REFERENCES "cash_flow_categories" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "cash_flow_categories" ADD CONSTRAINT FK_4E3C2D23979B1AD6 FOREIGN KEY (company_id) REFERENCES "companies" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "cash_flow_categories" DROP CONSTRAINT FK_4E3C2D23727ACA70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "cash_flow_categories" DROP CONSTRAINT FK_4E3C2D23979B1AD6
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "cash_flow_categories"
        SQL);
    }
}
