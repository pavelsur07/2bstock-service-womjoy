<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250607041403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE cash_flow_categories DROP CONSTRAINT fk_4e3c2d23979b1ad6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cash_flow_categories DROP CONSTRAINT fk_4e3c2d23727aca70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cash_flow_transactions DROP CONSTRAINT fk_2345409d979b1ad6
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cash_flow_categories
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cash_flow_transactions
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE cash_flow_categories (id UUID NOT NULL, company_id UUID NOT NULL, parent_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_4e3c2d23727aca70 ON cash_flow_categories (parent_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_4e3c2d23979b1ad6 ON cash_flow_categories (company_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE cash_flow_transactions (id UUID NOT NULL, company_id UUID DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, amount DOUBLE PRECISION NOT NULL, direction VARCHAR(60) NOT NULL, comment VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_2345409d979b1ad6 ON cash_flow_transactions (company_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN cash_flow_transactions.date IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cash_flow_categories ADD CONSTRAINT fk_4e3c2d23979b1ad6 FOREIGN KEY (company_id) REFERENCES companies (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cash_flow_categories ADD CONSTRAINT fk_4e3c2d23727aca70 FOREIGN KEY (parent_id) REFERENCES cash_flow_categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cash_flow_transactions ADD CONSTRAINT fk_2345409d979b1ad6 FOREIGN KEY (company_id) REFERENCES companies (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }
}
