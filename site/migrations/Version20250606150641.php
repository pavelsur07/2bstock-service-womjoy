<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250606150641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE "cash_flow_transactions" (id UUID NOT NULL, company_id UUID DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, amount DOUBLE PRECISION NOT NULL, direction VARCHAR(60) NOT NULL, comment VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2345409D979B1AD6 ON "cash_flow_transactions" (company_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "cash_flow_transactions".date IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "cash_flow_transactions" ADD CONSTRAINT FK_2345409D979B1AD6 FOREIGN KEY (company_id) REFERENCES "companies" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE company_user DROP CONSTRAINT fk_cefecca7979b1ad6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE company_user DROP CONSTRAINT fk_cefecca7a76ed395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE company_user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE companies ADD owner_id UUID DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE companies ADD CONSTRAINT FK_8244AA3A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8244AA3A7E3C61F9 ON companies (owner_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE company_user (company_id UUID NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(company_id, user_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_cefecca7a76ed395 ON company_user (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_cefecca7979b1ad6 ON company_user (company_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE company_user ADD CONSTRAINT fk_cefecca7979b1ad6 FOREIGN KEY (company_id) REFERENCES companies (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE company_user ADD CONSTRAINT fk_cefecca7a76ed395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "cash_flow_transactions" DROP CONSTRAINT FK_2345409D979B1AD6
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "cash_flow_transactions"
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "companies" DROP CONSTRAINT FK_8244AA3A7E3C61F9
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8244AA3A7E3C61F9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "companies" DROP owner_id
        SQL);
    }
}
