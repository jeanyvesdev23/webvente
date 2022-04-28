<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220427130823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire_blog (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, blog_id INT DEFAULT NULL, commentaire LONGTEXT NOT NULL, is_publier TINYINT(1) NOT NULL, number_etoile INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, update_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_29ED951167B3B43D (users_id), INDEX IDX_29ED9511DAE07E97 (blog_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire_blog ADD CONSTRAINT FK_29ED951167B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire_blog ADD CONSTRAINT FK_29ED9511DAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCDAE07E97');
        $this->addSql('DROP INDEX IDX_67F068BCDAE07E97 ON commentaire');
        $this->addSql('ALTER TABLE commentaire DROP blog_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE commentaire_blog');
        $this->addSql('ALTER TABLE addres CHANGE ville ville VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE parcelle parcelle VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE full_name full_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE numero numero VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE more_information more_information LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE blog CHANGE image_bog image_bog VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE titre_blog titre_blog VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description_blog description_blog LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE categorie CHANGE nom_cate nom_cate VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image_cate image_cate VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description_cate description_cate LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE icons icons VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE commande CHANGE address_livraison address_livraison VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE information information VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE commentaire ADD blog_id INT DEFAULT NULL, CHANGE commentaire commentaire LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCDAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('CREATE INDEX IDX_67F068BCDAE07E97 ON commentaire (blog_id)');
        $this->addSql('ALTER TABLE contact CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE subject subject VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE message message LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE marque CHANGE nom_marque nom_marque VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE entreprise entreprise VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image_marque image_marque VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE panier CHANGE name_product name_product VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image_product image_product VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description_product description_product LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE produit CHANGE nom_pro nom_pro VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image_pro image_pro VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description_pro description_pro LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE status_commande CHANGE status_livraison status_livraison VARCHAR(255) DEFAULT \'passée\' NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE type_livraison type_livraison VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE style style VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE status_paiement CHANGE is_payer is_payer VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE type_payer type_payer VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE style style VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nom_user nom_user VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE prenom_user prenom_user VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image_user image_user VARCHAR(255) DEFAULT \'children.jpg\' COLLATE `utf8mb4_unicode_ci`, CHANGE number_phone number_phone VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
