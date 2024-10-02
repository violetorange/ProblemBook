<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241001213102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE time_costs_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE time_costs (id INT NOT NULL, user_owner_id INT NOT NULL, task_id INT NOT NULL, description VARCHAR(255) DEFAULT NULL, time DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EA6FB6D49EB185F9 ON time_costs (user_owner_id)');
        $this->addSql('CREATE INDEX IDX_EA6FB6D48DB60186 ON time_costs (task_id)');
        $this->addSql('COMMENT ON COLUMN time_costs.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE time_costs ADD CONSTRAINT FK_EA6FB6D49EB185F9 FOREIGN KEY (user_owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE time_costs ADD CONSTRAINT FK_EA6FB6D48DB60186 FOREIGN KEY (task_id) REFERENCES tasks (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comments ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN comments.created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE time_costs_id_seq CASCADE');
        $this->addSql('ALTER TABLE time_costs DROP CONSTRAINT FK_EA6FB6D49EB185F9');
        $this->addSql('ALTER TABLE time_costs DROP CONSTRAINT FK_EA6FB6D48DB60186');
        $this->addSql('DROP TABLE time_costs');
        $this->addSql('ALTER TABLE comments ALTER created_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('COMMENT ON COLUMN comments.created_at IS NULL');
    }
}
