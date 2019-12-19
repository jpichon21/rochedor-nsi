<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191218141547 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        // @see MCD
        // tables not handled by Doctrine
        /*
        $this->addSql('DROP TABLE activ_l');
        $this->addSql('DROP TABLE auth_tokens');
        $this->addSql('DROP TABLE base_l2');
        $this->addSql('DROP TABLE bmem');
        $this->addSql('DROP TABLE cogrp');
        $this->addSql('DROP TABLE cogrp_l');
        $this->addSql('DROP TABLE donr');
        $this->addSql('DROP TABLE donr_1');
        $this->addSql('DROP TABLE donr_2');
        $this->addSql('DROP TABLE f_cf');
        $this->addSql('DROP TABLE gift');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE hebcal');
        $this->addSql('DROP TABLE hebergement');
        $this->addSql('DROP TABLE hebtab');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE message_l');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE tab_co');
        $this->addSql('DROP TABLE tcompte');
         */

        // missing changes :
        // - auth_token ?
        // - base ?
        // - base_l ?
        // - cal_l ?
        // - cal_l ?
        // - contact ?
        // - contact_l ?
        // - prodrub ?

        $this->addSql('CREATE TABLE actualite (idNews INT AUTO_INCREMENT NOT NULL, Intro LONGTEXT DEFAULT NULL, Description LONGTEXT NOT NULL, Url VARCHAR(255) DEFAULT NULL, Start DATETIME NOT NULL, Stop DATETIME NOT NULL, Locale VARCHAR(10) DEFAULT NULL, PRIMARY KEY(idNews)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE client ADD TvaIntra VARCHAR(20) NOT NULL, ADD Password VARCHAR(100) NOT NULL, ADD Reset_token VARCHAR(255) NOT NULL, ADD Reset_token_expires_at DATETIME NOT NULL, ADD Username VARCHAR(50) NOT NULL, ADD Professionnel TINYINT(1) NOT NULL, ADD DatConDonnees DATETIME DEFAULT CURRENT_TIMESTAMP, ADD DatNewsDonnees DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C74404551286421 ON client (Username)');

        $this->addSql('ALTER TABLE commande ADD AdFact LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', DROP PaysLiv, CHANGE CodCli CodCli INT UNSIGNED DEFAULT NULL, CHANGE AdLiv Adliv LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');

        $this->addSql('ALTER TABLE contact ADD ResetToken VARCHAR(50) DEFAULT NULL, ADD ResetTokenExpire DATETIME NOT NULL, ADD NewFich TINYINT(1) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C62E6381286421 ON contact (Username)');

        $this->addSql('ALTER TABLE don CHANGE DonCo DonCo INT DEFAULT NULL, CHANGE status Status VARCHAR(10) NOT NULL');

        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');

        $this->addSql('CREATE TABLE image (idImage INT AUTO_INCREMENT NOT NULL, Chemin VARCHAR(255) NOT NULL, Creation DATETIME NOT NULL, Maj DATETIME NOT NULL, UNIQUE INDEX UNIQ_C53D045FC38E261E (Chemin), PRIMARY KEY(idImage)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE intervenant (idInter INT AUTO_INCREMENT NOT NULL, Nom VARCHAR(50) NOT NULL, Titre LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', Description LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', Image VARCHAR(255) NOT NULL, position INT NOT NULL, PRIMARY KEY(idInter)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE orm_routes (id INT AUTO_INCREMENT NOT NULL, host VARCHAR(255) NOT NULL, schemes LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', methods LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', defaults LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', requirements LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', options LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', condition_expr VARCHAR(255) DEFAULT NULL, variable_pattern VARCHAR(255) DEFAULT NULL, staticPrefix VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, position INT NOT NULL, UNIQUE INDEX UNIQ_5793FC5E237E06 (name), INDEX name_idx (name), INDEX prefix_idx (staticPrefix), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE packaging (idPack INT AUTO_INCREMENT NOT NULL, Limitation INT NOT NULL, France INT NOT NULL, International INT NOT NULL, PRIMARY KEY(idPack)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, Title VARCHAR(100) NOT NULL, SubTitle VARCHAR(100) NOT NULL, Description VARCHAR(255) DEFAULT NULL, Content LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', Background INT DEFAULT NULL, Locale VARCHAR(10) DEFAULT NULL, Maj DATETIME DEFAULT NULL, Immutableid VARCHAR(100) NOT NULL, Type VARCHAR(20) NOT NULL, Category VARCHAR(255) NOT NULL, INDEX IDX_140AB620727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620727ACA70 FOREIGN KEY (parent_id) REFERENCES page (id)');

        $this->addSql('CREATE TABLE page_route (page_id INT NOT NULL, route_id INT NOT NULL, INDEX IDX_6445DFAFC4663E4 (page_id), INDEX IDX_6445DFAF34ECB4E6 (route_id), PRIMARY KEY(page_id, route_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE page_route ADD CONSTRAINT FK_6445DFAFC4663E4 FOREIGN KEY (page_id) REFERENCES page (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE page_route ADD CONSTRAINT FK_6445DFAF34ECB4E6 FOREIGN KEY (route_id) REFERENCES orm_routes (id) ON DELETE CASCADE');

        $this->addSql('CREATE TABLE panier (idPanier INT AUTO_INCREMENT NOT NULL, Updated DATETIME NOT NULL, Created DATETIME NOT NULL, PRIMARY KEY(idPanier)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE produit ADD Produitlong LONGTEXT NOT NULL, ADD Ean CHAR(20) NOT NULL, ADD Editeur VARCHAR(40) NOT NULL, ADD Datparution DATETIME NOT NULL, ADD PrixHt NUMERIC(10, 2) NOT NULL, ADD Epaisseur SMALLINT NOT NULL, ADD AdImg2 TEXT NOT NULL, ADD AdImg3 TEXT NOT NULL, ADD Presentation TEXT NOT NULL, ADD Nouveaute TINYINT(1) NOT NULL, ADD Themes TINYTEXT NOT NULL, DROP Annee');

        $this->addSql('CREATE TABLE regles_taxes (IdTax INT AUTO_INCREMENT NOT NULL, LibTax VARCHAR(20) NOT NULL, Taux NUMERIC(10, 2) NOT NULL, Pays LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', TypPrd VARCHAR(5) NOT NULL, PRIMARY KEY(IdTax)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE tpays ADD CodPaysPBX VARCHAR(3) NOT NULL, ADD CodPaysPaypal VARCHAR(5) NOT NULL, ADD CodPostaux LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', ADD MinLiv INT NOT NULL, ADD MaxLiv INT NOT NULL, ADD DispLiv TINYINT(1) NOT NULL, CHANGE CodPays CodPays VARCHAR(2) NOT NULL');

        $this->addSql('CREATE TABLE transport (idTrans INT AUTO_INCREMENT NOT NULL, LibPort VARCHAR(20) NOT NULL, Poids INT NOT NULL, Prix NUMERIC(10, 2) NOT NULL, Pays LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(idTrans)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE utilisateur_adm (Id INT AUTO_INCREMENT NOT NULL, Username VARCHAR(25) NOT NULL, Email VARCHAR(50) NOT NULL, Password VARCHAR(60) NOT NULL, Name VARCHAR(60) DEFAULT NULL, Active TINYINT(1) NOT NULL, Roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', ResetToken VARCHAR(50) DEFAULT NULL, ResetTokenExpiresAt DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_98A77301286421 (Username), UNIQUE INDEX UNIQ_98A773026535370 (Email), PRIMARY KEY(Id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE ligne_panier (panier INT DEFAULT NULL, idLPanier INT AUTO_INCREMENT NOT NULL, Qte INT NOT NULL, IdProd INT NOT NULL, INDEX IDX_21691B4C1E54A3F (IdProd), INDEX IDX_21691B424CC0DF2 (panier), PRIMARY KEY(idLPanier)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE ligne_panier ADD CONSTRAINT FK_21691B424CC0DF2 FOREIGN KEY (panier) REFERENCES panier (idPanier)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB620727ACA70');
        $this->addSql('ALTER TABLE page_route DROP FOREIGN KEY FK_6445DFAFC4663E4');
        $this->addSql('ALTER TABLE ligne_panier DROP FOREIGN KEY FK_21691B424CC0DF2');
        $this->addSql('ALTER TABLE page_route DROP FOREIGN KEY FK_6445DFAF34ECB4E6');
        $this->addSql('DROP TABLE ext_log_entries');
        $this->addSql('DROP TABLE regles_taxes');
        $this->addSql('DROP TABLE transport');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE page_route');
        $this->addSql('DROP TABLE packaging');
        $this->addSql('DROP TABLE utilisateur_adm');
        $this->addSql('DROP TABLE actualite');
        $this->addSql('DROP TABLE intervenant');
        $this->addSql('DROP TABLE ligne_panier');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE orm_routes');
        $this->addSql('DROP INDEX UNIQ_C74404551286421 ON client');
        $this->addSql('ALTER TABLE client DROP TvaIntra, DROP Password, DROP Reset_token, DROP Reset_token_expires_at, DROP Username, DROP Professionnel, DROP DatConDonnees, DROP DatNewsDonnees');
        $this->addSql('ALTER TABLE commande ADD PaysLiv VARCHAR(2) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, DROP AdFact, CHANGE CodCli CodCli INT UNSIGNED NOT NULL, CHANGE Adliv AdLiv TEXT CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`');
        $this->addSql('DROP INDEX UNIQ_4C62E6381286421 ON contact');
        $this->addSql('ALTER TABLE contact DROP ResetToken, DROP ResetTokenExpire, DROP NewFich');
        $this->addSql('ALTER TABLE don DROP FOREIGN KEY FK_F8F081D94C0468E4');
        $this->addSql('ALTER TABLE don CHANGE Status status VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, CHANGE DonCo DonCo INT NOT NULL');
        $this->addSql('ALTER TABLE produit ADD Annee VARCHAR(4) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, DROP Produitlong, DROP Ean, DROP Editeur, DROP Datparution, DROP PrixHt, DROP Epaisseur, DROP AdImg2, DROP AdImg3, DROP Presentation, DROP Nouveaute, DROP Themes');
        $this->addSql('ALTER TABLE tpays DROP CodPaysPBX, DROP CodPaysPaypal, DROP CodPostaux, DROP MinLiv, DROP MaxLiv, DROP DispLiv, CHANGE CodPays CodPays VARCHAR(2) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`');
    }
}
