<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250607051523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE "cash_flow_transactions" (id UUID NOT NULL, category_id UUID NOT NULL, company_id UUID NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, amount DOUBLE PRECISION NOT NULL, direction VARCHAR(10) NOT NULL, comment TEXT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2345409D12469DE2 ON "cash_flow_transactions" (category_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2345409D979B1AD6 ON "cash_flow_transactions" (company_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "cash_flow_transactions".date IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "cash_flow_transactions" ADD CONSTRAINT FK_2345409D12469DE2 FOREIGN KEY (category_id) REFERENCES "cash_flow_categories" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "cash_flow_transactions" ADD CONSTRAINT FK_2345409D979B1AD6 FOREIGN KEY (company_id) REFERENCES "companies" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "cash_flow_transactions" DROP CONSTRAINT FK_2345409D12469DE2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "cash_flow_transactions" DROP CONSTRAINT FK_2345409D979B1AD6
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "cash_flow_transactions"
        SQL);
    }
}
