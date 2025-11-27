<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251127091631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateur_id INTEGER NOT NULL, date_commande DATETIME DEFAULT NULL, total NUMERIC(10, 2) NOT NULL, etat INTEGER NOT NULL, CONSTRAINT FK_6EEAA67DFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DFB88E14F ON commande (utilisateur_id)');
        $this->addSql('CREATE TABLE detail (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, plat_id INTEGER NOT NULL, commande_id INTEGER NOT NULL, quantite INTEGER NOT NULL, CONSTRAINT FK_2E067F93D73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2E067F9382EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_2E067F93D73DB560 ON detail (plat_id)');
        $this->addSql('CREATE INDEX IDX_2E067F9382EA2E54 ON detail (commande_id)');
        $this->addSql('CREATE TABLE plat (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, prix DOUBLE PRECISION NOT NULL, film VARCHAR(255) NOT NULL, detail VARCHAR(500) NOT NULL, photo VARCHAR(255) NOT NULL, categorie VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE utilisateur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, token VARCHAR(255) DEFAULT NULL, nom VARCHAR(50) DEFAULT NULL, prenom VARCHAR(50) DEFAULT NULL, telephone VARCHAR(20) DEFAULT NULL, adresse VARCHAR(50) DEFAULT NULL, cp VARCHAR(20) DEFAULT NULL, ville VARCHAR(50) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON utilisateur (email)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE detail');
        $this->addSql('DROP TABLE plat');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
