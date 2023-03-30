<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230330203452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ad (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, introduction CLOB NOT NULL, content CLOB NOT NULL, cover_image VARCHAR(255) NOT NULL, rooms INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , type VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zip_code VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, CONSTRAINT FK_77E0ED58F675F31B FOREIGN KEY (author_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_77E0ED58F675F31B ON ad (author_id)');
        $this->addSql('CREATE TABLE booking (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, booker_id INTEGER NOT NULL, ad_id INTEGER NOT NULL, start_date DATETIME NOT NULL, created_at DATETIME NOT NULL, amount DOUBLE PRECISION NOT NULL, reservation_date DATETIME NOT NULL, comment VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_E00CEDDE8B7E4006 FOREIGN KEY (booker_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E00CEDDE4F34D596 FOREIGN KEY (ad_id) REFERENCES ad (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE8B7E4006 ON booking (booker_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE4F34D596 ON booking (ad_id)');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, ad_id INTEGER NOT NULL, created_at DATETIME NOT NULL, rating INTEGER NOT NULL, content CLOB NOT NULL, CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_9474526C4F34D596 FOREIGN KEY (ad_id) REFERENCES ad (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_9474526CF675F31B ON comment (author_id)');
        $this->addSql('CREATE INDEX IDX_9474526C4F34D596 ON comment (ad_id)');
        $this->addSql('CREATE TABLE contact (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, message CLOB NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , is_read BOOLEAN NOT NULL)');
        $this->addSql('CREATE TABLE image (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ad_id INTEGER NOT NULL, url VARCHAR(255) NOT NULL, caption VARCHAR(255) NOT NULL, CONSTRAINT FK_C53D045F4F34D596 FOREIGN KEY (ad_id) REFERENCES ad (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_C53D045F4F34D596 ON image (ad_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(100) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, introduction VARCHAR(255) NOT NULL, presentation CLOB NOT NULL, slug VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, profil_picture VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , phone VARCHAR(255) NOT NULL, is_verified BOOLEAN DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ad');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
