<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240912213916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE participants_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE participants (id INT NOT NULL, project_id INT NOT NULL, participant_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_71697092166D1F9C ON participants (project_id)');
        $this->addSql('CREATE INDEX IDX_716970929D1C3019 ON participants (participant_id)');
        $this->addSql('ALTER TABLE participants ADD CONSTRAINT FK_71697092166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participants ADD CONSTRAINT FK_716970929D1C3019 FOREIGN KEY (participant_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE participants_id_seq CASCADE');
        $this->addSql('ALTER TABLE participants DROP CONSTRAINT FK_71697092166D1F9C');
        $this->addSql('ALTER TABLE participants DROP CONSTRAINT FK_716970929D1C3019');
        $this->addSql('DROP TABLE participants');
    }
}
