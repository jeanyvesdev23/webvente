<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220421155846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande CHANGE status_commandes_id status_commandes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE status_commande CHANGE style style VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE status_paiement CHANGE style style VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD image_user VARCHAR(255) DEFAULT NULL, ADD number_phone VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE addres CHANGE ville ville VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE parcelle parcelle VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE full_name full_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE numero numero VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE more_information more_information LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE categorie CHANGE nom_cate nom_cate VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image_cate image_cate VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description_cate description_cate LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE icons icons VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE commande CHANGE status_commandes_id status_commandes_id INT DEFAULT 1, CHANGE address_livraison address_livraison VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE information information VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE marque CHANGE nom_marque nom_marque VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE entreprise entreprise VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image_marque image_marque VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE panier CHANGE name_product name_product VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image_product image_product VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE produit CHANGE nom_pro nom_pro VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image_pro image_pro VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description_pro description_pro LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE status_commande CHANGE status_livraison status_livraison VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE type_livraison type_livraison VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE style style VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE status_paiement CHANGE is_payer is_payer VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE type_payer type_payer VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE style style VARCHAR(56) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user DROP image_user, DROP number_phone, CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nom_user nom_user VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE prenom_user prenom_user VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
