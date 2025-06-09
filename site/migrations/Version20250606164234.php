<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250606164234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE cash_flow_categories ADD parent_id UUID DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cash_flow_categories ALTER company_id SET NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cash_flow_categories ADD CONSTRAINT FK_4E3C2D23727ACA70 FOREIGN KEY (parent_id) REFERENCES "cash_flow_categories" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4E3C2D23727ACA70 ON cash_flow_categories (parent_id)
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
            DROP INDEX IDX_4E3C2D23727ACA70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "cash_flow_categories" DROP parent_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "cash_flow_categories" ALTER company_id DROP NOT NULL
        SQL);
    }
}
