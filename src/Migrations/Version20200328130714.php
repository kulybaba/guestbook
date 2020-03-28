<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200328130714 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE speaker_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE city_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE conference_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE speaker (id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, site VARCHAR(255) DEFAULT NULL, position INT DEFAULT NULL, visible BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE city (id INT NOT NULL, name VARCHAR(255) NOT NULL, position INT DEFAULT NULL, visible BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE conference (id INT NOT NULL, city_id INT NOT NULL, title VARCHAR(255) NOT NULL, body TEXT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, visible BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_911533C88BAC62AF ON conference (city_id)');
        $this->addSql('CREATE TABLE conference_category (conference_id INT NOT NULL, category_id INT NOT NULL, PRIMARY KEY(conference_id, category_id))');
        $this->addSql('CREATE INDEX IDX_C30DDFE0604B8382 ON conference_category (conference_id)');
        $this->addSql('CREATE INDEX IDX_C30DDFE012469DE2 ON conference_category (category_id)');
        $this->addSql('CREATE TABLE conference_speaker (conference_id INT NOT NULL, speaker_id INT NOT NULL, PRIMARY KEY(conference_id, speaker_id))');
        $this->addSql('CREATE INDEX IDX_807844D9604B8382 ON conference_speaker (conference_id)');
        $this->addSql('CREATE INDEX IDX_807844D9D04A0F27 ON conference_speaker (speaker_id)');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, conference_id INT NOT NULL, text VARCHAR(255) NOT NULL, visible BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526C604B8382 ON comment (conference_id)');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, name VARCHAR(255) NOT NULL, position INT DEFAULT NULL, visible BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, city_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6498BAC62AF ON "user" (city_id)');
        $this->addSql('ALTER TABLE conference ADD CONSTRAINT FK_911533C88BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE conference_category ADD CONSTRAINT FK_C30DDFE0604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE conference_category ADD CONSTRAINT FK_C30DDFE012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE conference_speaker ADD CONSTRAINT FK_807844D9604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE conference_speaker ADD CONSTRAINT FK_807844D9D04A0F27 FOREIGN KEY (speaker_id) REFERENCES speaker (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6498BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE conference_speaker DROP CONSTRAINT FK_807844D9D04A0F27');
        $this->addSql('ALTER TABLE conference DROP CONSTRAINT FK_911533C88BAC62AF');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6498BAC62AF');
        $this->addSql('ALTER TABLE conference_category DROP CONSTRAINT FK_C30DDFE0604B8382');
        $this->addSql('ALTER TABLE conference_speaker DROP CONSTRAINT FK_807844D9604B8382');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C604B8382');
        $this->addSql('ALTER TABLE conference_category DROP CONSTRAINT FK_C30DDFE012469DE2');
        $this->addSql('DROP SEQUENCE speaker_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE city_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE conference_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('DROP TABLE speaker');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE conference');
        $this->addSql('DROP TABLE conference_category');
        $this->addSql('DROP TABLE conference_speaker');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE "user"');
    }
}
