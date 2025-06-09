<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250607100452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE cash_flow_transactions ADD account_id UUID NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cash_flow_transactions ADD CONSTRAINT FK_2345409D9B6B5FBA FOREIGN KEY (account_id) REFERENCES "cash_accounts" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2345409D9B6B5FBA ON cash_flow_transactions (account_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "cash_flow_transactions" DROP CONSTRAINT FK_2345409D9B6B5FBA
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_2345409D9B6B5FBA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "cash_flow_transactions" DROP account_id
        SQL);
    }
}
