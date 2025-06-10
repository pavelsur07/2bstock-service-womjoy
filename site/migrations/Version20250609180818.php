<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250609180818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE cash_flow_transactions ADD project_id UUID DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cash_flow_transactions ADD CONSTRAINT FK_2345409D166D1F9C FOREIGN KEY (project_id) REFERENCES "projects" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2345409D166D1F9C ON cash_flow_transactions (project_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "cash_flow_transactions" DROP CONSTRAINT FK_2345409D166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_2345409D166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "cash_flow_transactions" DROP project_id
        SQL);
    }
}
