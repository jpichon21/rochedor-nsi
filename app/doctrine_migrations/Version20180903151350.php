<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180903151350 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE activ_l');
        $this->addSql('DROP TABLE auth_tokens');
        $this->addSql('DROP TABLE base_l2');
        $this->addSql('DROP TABLE bmem');
        $this->addSql('DROP TABLE cogrp');
        $this->addSql('DROP TABLE cogrp_l');
        $this->addSql('DROP TABLE don');
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
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE tax DROP zipcodes, CHANGE name name VARCHAR(20) NOT NULL, CHANGE rate rate NUMERIC(10, 2) NOT NULL, CHANGE countries countries TEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE shipping ADD countries TEXT NOT NULL COMMENT \'(DC2Type:json)\', DROP country, CHANGE name name VARCHAR(20) NOT NULL, CHANGE weight weight INT NOT NULL');
        $this->addSql('ALTER TABLE tpays ADD CodPaysPBX VARCHAR(3) NOT NULL, ADD CodPaysPaypal VARCHAR(5) NOT NULL, ADD CodPostaux TEXT NOT NULL COMMENT \'(DC2Type:json)\', CHANGE CodPays CodPays VARCHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE produit CHANGE Editeur Editeur VARCHAR(40) DEFAULT NULL, CHANGE produitcourt Produit VARCHAR(80) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C62E638F85E0677 ON contact (username)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE activ_l (CodActl INT NOT NULL, CodAct INT NOT NULL, CodBAct INT NOT NULL, TypActl VARCHAR(6) NOT NULL COLLATE latin1_swedish_ci, TablAct VARCHAR(6) NOT NULL COLLATE latin1_swedish_ci, INDEX CodAct (CodAct, CodBAct), PRIMARY KEY(CodActl)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auth_tokens (id INT AUTO_INCREMENT NOT NULL, contact_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, created_at DATETIME NOT NULL, UNIQUE INDEX auth_tokens_value_unique (value), INDEX IDX_8AF9B66CE7A1254A (contact_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE base_l2 (Codl2 INT AUTO_INCREMENT NOT NULL, Cle INT NOT NULL, CodBl2 INT NOT NULL, cl1 INT NOT NULL, Typl2 VARCHAR(6) NOT NULL COLLATE latin1_swedish_ci, bl2 INT NOT NULL, Form TEXT NOT NULL COLLATE latin1_swedish_ci, Data TEXT NOT NULL COLLATE latin1_swedish_ci, INDEX Cle (Cle), PRIMARY KEY(Codl2)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bmem (CodMem INT AUTO_INCREMENT NOT NULL, CB INT NOT NULL, TypMem VARCHAR(6) NOT NULL COLLATE latin1_swedish_ci, ValMem VARCHAR(6) NOT NULL COLLATE latin1_swedish_ci, Lang VARCHAR(2) NOT NULL COLLATE latin1_swedish_ci, Memo TEXT NOT NULL COLLATE latin1_swedish_ci, VerMem VARCHAR(20) NOT NULL COLLATE latin1_swedish_ci, UserMem VARCHAR(15) NOT NULL COLLATE latin1_swedish_ci, TAMem VARCHAR(6) NOT NULL COLLATE latin1_swedish_ci, MajMem DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX CB (CB), PRIMARY KEY(CodMem)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cogrp (idCoG INT AUTO_INCREMENT NOT NULL, cogNom VARCHAR(40) NOT NULL COLLATE latin1_swedish_ci, cogTyp VARCHAR(6) NOT NULL COLLATE latin1_swedish_ci, cogPerso TINYINT(1) NOT NULL, cogQui VARCHAR(12) NOT NULL COLLATE latin1_swedish_ci, cogMemo TEXT NOT NULL COLLATE latin1_swedish_ci, cogEnreg DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(idCoG)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cogrp_l (idCoGl INT AUTO_INCREMENT NOT NULL, coglGrp INT NOT NULL, coglCo INT NOT NULL, Result SMALLINT NOT NULL, DatEnvoi DATETIME NOT NULL, Etat VARCHAR(10) NOT NULL COLLATE latin1_swedish_ci, Essai SMALLINT NOT NULL, ResTxt TEXT NOT NULL COLLATE latin1_swedish_ci, UNIQUE INDEX `index` (coglGrp, coglCo), PRIMARY KEY(idCoGl)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE don (CodDon INT AUTO_INCREMENT NOT NULL, RefDon VARCHAR(9) DEFAULT NULL COLLATE latin1_swedish_ci, DonCo INT DEFAULT NULL, MntDon NUMERIC(10, 2) DEFAULT NULL, MonDon VARCHAR(2) DEFAULT NULL COLLATE latin1_swedish_ci, DestDon VARCHAR(6) DEFAULT NULL COLLATE latin1_swedish_ci, BanqDon INT DEFAULT NULL, ModDon VARCHAR(6) DEFAULT NULL COLLATE latin1_swedish_ci, noDonR INT DEFAULT NULL, ValidDon TINYINT(1) DEFAULT NULL, noRecu TINYINT(1) DEFAULT NULL, Adhesion TINYINT(1) DEFAULT NULL, MemoDon TEXT DEFAULT NULL COLLATE latin1_swedish_ci, DatDon DATETIME DEFAULT NULL, DatRecu DATE DEFAULT NULL, TransDon VARCHAR(20) DEFAULT NULL COLLATE latin1_swedish_ci, PaysDon VARCHAR(4) DEFAULT NULL COLLATE latin1_swedish_ci, MsgBanq VARCHAR(20) DEFAULT NULL COLLATE latin1_swedish_ci, EnregDon DATETIME DEFAULT NULL, CreatDon VARCHAR(15) DEFAULT NULL COLLATE latin1_swedish_ci, OldDon INT DEFAULT NULL, NomMerci VARCHAR(25) NOT NULL COLLATE latin1_swedish_ci, DatMerci DATE DEFAULT NULL, MoyenMerci CHAR(8) NOT NULL COLLATE latin1_swedish_ci, status VARCHAR(255) DEFAULT NULL COLLATE latin1_swedish_ci, INDEX DonCo (DonCo), INDEX BanqDon (BanqDon), PRIMARY KEY(CodDon)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donr (CodDonR INT AUTO_INCREMENT NOT NULL, RefDon VARCHAR(9) DEFAULT NULL COLLATE latin1_swedish_ci, DonRCo INT DEFAULT NULL, DestDon VARCHAR(6) DEFAULT NULL COLLATE latin1_swedish_ci, MntDon NUMERIC(10, 2) DEFAULT NULL, MonDonR VARCHAR(2) DEFAULT NULL COLLATE latin1_swedish_ci, BanqDon INT DEFAULT NULL, Banque VARCHAR(45) DEFAULT NULL COLLATE latin1_swedish_ci, ModDon VARCHAR(6) DEFAULT NULL COLLATE latin1_swedish_ci, CpteBanq VARCHAR(40) DEFAULT NULL COLLATE latin1_swedish_ci, AdBanq TEXT DEFAULT NULL COLLATE latin1_swedish_ci, CPBanq VARCHAR(5) DEFAULT NULL COLLATE latin1_swedish_ci, VilBanq VARCHAR(30) DEFAULT NULL COLLATE latin1_swedish_ci, PaysBanq VARCHAR(20) DEFAULT NULL COLLATE latin1_swedish_ci, DatVir DATE DEFAULT NULL, VirFin DATE DEFAULT NULL, VirFreq VARCHAR(1) DEFAULT NULL COLLATE latin1_swedish_ci, DatTrans DATE DEFAULT NULL, CreatDonR VARCHAR(15) DEFAULT NULL COLLATE latin1_swedish_ci, EnregDonR DATETIME DEFAULT NULL, status VARCHAR(255) DEFAULT NULL COLLATE latin1_swedish_ci, INDEX DestDon (DestDon), INDEX CodCo_l (DonRCo), INDEX BanqDon (BanqDon), PRIMARY KEY(CodDonR)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donr_1 (IdRetraitant INT NOT NULL, IdCompteBancaire TINYINT(1) NOT NULL, Montant NUMERIC(10, 2) NOT NULL, Nb TINYINT(1) NOT NULL) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donr_2 (IdRetraitant INT NOT NULL, IdCompteBancaire TINYINT(1) NOT NULL, Montant NUMERIC(10, 2) NOT NULL, Nb TINYINT(1) NOT NULL, Date1 DATE NOT NULL) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE f_cf (idCF INT AUTO_INCREMENT NOT NULL COMMENT \'Commentaire Fondamental\', bCF INT NOT NULL, Appreciation TEXT NOT NULL COLLATE latin1_swedish_ci, Cassette VARCHAR(20) NOT NULL COLLATE latin1_swedish_ci, Refer VARCHAR(30) NOT NULL COLLATE latin1_swedish_ci, Tonalite VARCHAR(20) NOT NULL COLLATE latin1_swedish_ci, Caracteristique VARCHAR(15) NOT NULL COLLATE latin1_swedish_ci, Video TINYINT(1) NOT NULL, Fichier VARCHAR(60) NOT NULL COLLATE latin1_swedish_ci, Theme VARCHAR(6) NOT NULL COLLATE latin1_swedish_ci COMMENT \'La résurrection/La mort n\'\'eixte pas/de l\'\'exteriorité à l\'\'intériorité + Dieu créateur créant + La vie dans l\'\'Esprit Saint/Demeurer dans la parole/La nouvelle Alliance\', Predicateur VARCHAR(6) NOT NULL COLLATE latin1_swedish_ci, FichDeb DATE NOT NULL, FichFin DATE NOT NULL, INDEX bCF (bCF), PRIMARY KEY(idCF)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gift (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ref VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, dest LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:json_array)\', type VARCHAR(1) DEFAULT NULL COLLATE utf8_unicode_ci, mode VARCHAR(3) DEFAULT NULL COLLATE utf8_unicode_ci, codco INT NOT NULL, created DATETIME NOT NULL, datvir DATETIME DEFAULT NULL, rAmount INT DEFAULT NULL, rDest VARCHAR(20) DEFAULT NULL COLLATE utf8_unicode_ci, period VARCHAR(20) DEFAULT NULL COLLATE utf8_unicode_ci, email VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, receipt TINYINT(1) DEFAULT NULL, error VARCHAR(50) DEFAULT NULL COLLATE utf8_unicode_ci, house VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_A47C990D146F3EA3 (ref), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe (idGrp INT AUTO_INCREMENT NOT NULL, LienG INT NOT NULL, CalG INT NOT NULL, CivilG VARCHAR(6) NOT NULL COLLATE latin1_swedish_ci, NomG VARCHAR(50) NOT NULL COLLATE latin1_swedish_ci, PrenomG VARCHAR(30) NOT NULL COLLATE latin1_swedish_ci, nbG SMALLINT NOT NULL, DebG DATETIME NOT NULL, FinG DATETIME NOT NULL, MemoG TEXT NOT NULL COLLATE latin1_swedish_ci, PRIMARY KEY(idGrp)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hebcal (hcid INT AUTO_INCREMENT NOT NULL, hcHeb INT NOT NULL, hcCo INT NOT NULL, hcIns INT NOT NULL, hcTab VARCHAR(6) NOT NULL COLLATE latin1_swedish_ci, hcDeb DATETIME NOT NULL, hcFin DATETIME NOT NULL, hcValid SMALLINT NOT NULL, INDEX hcCo (hcCo), INDEX hcHeb (hcHeb), PRIMARY KEY(hcid)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hebergement (hid INT AUTO_INCREMENT NOT NULL, hLien INT NOT NULL, hTyp VARCHAR(6) NOT NULL COLLATE latin1_swedish_ci, hTyp2 VARCHAR(6) NOT NULL COLLATE latin1_swedish_ci, hLib VARCHAR(50) NOT NULL COLLATE latin1_swedish_ci, hRef VARCHAR(20) NOT NULL COLLATE latin1_swedish_ci, hConf INT NOT NULL, hModel INT NOT NULL, hPlace INT NOT NULL, hNb SMALLINT NOT NULL, hh NUMERIC(6, 2) NOT NULL, hl NUMERIC(6, 2) NOT NULL, hw NUMERIC(6, 2) NOT NULL, hNiv SMALLINT NOT NULL, hPath VARCHAR(30) NOT NULL COLLATE latin1_swedish_ci, hRang SMALLINT NOT NULL, hAd TEXT NOT NULL COLLATE latin1_swedish_ci, hEnreg TEXT NOT NULL COLLATE latin1_swedish_ci, hMemo TEXT NOT NULL COLLATE latin1_swedish_ci, PRIMARY KEY(hid)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hebtab (htid INT AUTO_INCREMENT NOT NULL, htHeb INT NOT NULL, htTab INT NOT NULL, htDom VARCHAR(6) NOT NULL COLLATE latin1_swedish_ci, htValN INT NOT NULL, htValT VARCHAR(6) NOT NULL COLLATE latin1_swedish_ci, INDEX htHeb (htHeb), INDEX htTab (htTab), PRIMARY KEY(htid)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (Mid INT AUTO_INCREMENT NOT NULL, MLien INT NOT NULL, MCod INT NOT NULL, MTyp VARCHAR(6) NOT NULL COLLATE latin1_swedish_ci, MTit VARCHAR(80) NOT NULL COLLATE latin1_swedish_ci, Msg TEXT NOT NULL COLLATE latin1_swedish_ci, MData TEXT NOT NULL COLLATE latin1_swedish_ci, MEnreg DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX MLien (MLien), PRIMARY KEY(Mid)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_l (Mlid INT AUTO_INCREMENT NOT NULL, MlMid INT NOT NULL, MlCo INT NOT NULL, MlTyp VARCHAR(6) NOT NULL COLLATE latin1_swedish_ci, INDEX MlCo (MlCo), INDEX MlMid (MlMid), PRIMARY KEY(Mlid)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, amount DOUBLE PRECISION DEFAULT NULL, created DATETIME NOT NULL, method VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, status VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, error VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, trans VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, house VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet (pjid INT AUTO_INCREMENT NOT NULL, CodB INT NOT NULL, pjEtat INT NOT NULL, pjPrio SMALLINT NOT NULL, pjDeb DATETIME NOT NULL, pjFin DATETIME NOT NULL, pjObj DATETIME NOT NULL, pjTps INT NOT NULL, pjFait INT NOT NULL, pjU CHAR(2) NOT NULL COLLATE latin1_swedish_ci, INDEX CodB (CodB), PRIMARY KEY(pjid)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tab_co (CodT INT AUTO_INCREMENT NOT NULL, CoT INT NOT NULL, LienT INT NOT NULL, NivT INT NOT NULL, dateT DATETIME NOT NULL, Typ1T INT NOT NULL, Typ2T INT NOT NULL, MemoT TEXT NOT NULL COLLATE latin1_swedish_ci, INDEX LienT (LienT), INDEX CoT (CoT), PRIMARY KEY(CodT)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcompte (idCpte INT AUTO_INCREMENT NOT NULL, Cpte VARCHAR(20) NOT NULL COLLATE latin1_swedish_ci, NomCP VARCHAR(80) NOT NULL COLLATE latin1_swedish_ci, NumCP VARCHAR(30) NOT NULL COLLATE latin1_swedish_ci, Banque VARCHAR(10) NOT NULL COLLATE latin1_swedish_ci, UNIQUE INDEX idCpte (idCpte)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(25) NOT NULL COLLATE utf8_unicode_ci, password VARCHAR(64) NOT NULL COLLATE utf8_unicode_ci, email VARCHAR(254) NOT NULL COLLATE utf8_unicode_ci, name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, is_active TINYINT(1) NOT NULL, roles LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP INDEX UNIQ_4C62E638F85E0677 ON contact');
        $this->addSql('ALTER TABLE produit CHANGE Editeur Editeur VARCHAR(255) DEFAULT NULL COLLATE latin1_swedish_ci, CHANGE produit Produitcourt VARCHAR(80) NOT NULL COLLATE latin1_swedish_ci');
        $this->addSql('ALTER TABLE shipping ADD country VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP countries, CHANGE name name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE weight weight VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE tax ADD zipcodes LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', CHANGE name name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE rate rate DOUBLE PRECISION NOT NULL, CHANGE countries countries LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE tpays DROP CodPaysPBX, DROP CodPaysPaypal, DROP CodPostaux, CHANGE CodPays CodPays VARCHAR(2) NOT NULL COLLATE utf8_unicode_ci');
    }
}
