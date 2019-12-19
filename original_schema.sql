SET NAMES utf8;
SET time_zone = '+00:00';

CREATE TABLE `activite` (
  `CodAct` int(11) NOT NULL AUTO_INCREMENT,
  `LibAct` varchar(150) NOT NULL,
  `Recur` varchar(6) NOT NULL,
  `SitAct` varchar(6) NOT NULL,
  `DivAct` varchar(6) NOT NULL,
  `TypAct` varchar(6) NOT NULL,
  `Referent` varchar(20) NOT NULL,
  `MemoAct` text NOT NULL,
  `InfoAct` text NOT NULL,
  `CodOld` varchar(10) NOT NULL,
  PRIMARY KEY (`CodAct`),
  KEY `CoAct` (`Referent`)
) ENGINE=MyISAM AUTO_INCREMENT=3109 DEFAULT CHARSET=latin1;


CREATE TABLE `activ_l` (
  `CodActl` int(11) NOT NULL,
  `CodAct` int(11) NOT NULL,
  `CodBAct` int(11) NOT NULL,
  `TypActl` varchar(6) NOT NULL,
  `TablAct` varchar(6) NOT NULL,
  PRIMARY KEY (`CodActl`),
  KEY `CodAct` (`CodAct`,`CodBAct`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


CREATE TABLE `auth_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) DEFAULT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `auth_tokens_value_unique` (`value`),
  KEY `IDX_8AF9B66CE7A1254A` (`contact_id`)
) ENGINE=InnoDB AUTO_INCREMENT=581 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `base` (
  `Cle` int(11) NOT NULL AUTO_INCREMENT,
  `Titre` varchar(80) NOT NULL,
  `Ref` varchar(30) NOT NULL,
  `TypDoc` varchar(10) NOT NULL,
  `Conf` int(11) NOT NULL,
  `Etat` varchar(6) NOT NULL,
  `DAdm` tinyint(4) NOT NULL,
  `DAj` tinyint(4) NOT NULL,
  `DAff` tinyint(4) NOT NULL,
  `bmem` int(11) NOT NULL,
  `Hide` tinyint(4) NOT NULL,
  `Target` varchar(12) NOT NULL,
  `Createur` varchar(15) NOT NULL,
  `Archiv` tinyint(4) NOT NULL,
  `DatDoc` datetime NOT NULL,
  `AdrWeb` text NOT NULL,
  `Info` text NOT NULL,
  `Droit` text NOT NULL,
  `Dest` text NOT NULL,
  `Descript` text NOT NULL,
  `Enreg` text NOT NULL,
  `Data` text NOT NULL,
  `Rapport` text NOT NULL,
  `Sel` tinyint(4) NOT NULL,
  `CodCrea` int(11) NOT NULL,
  `DatEnreg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DatMaj` datetime NOT NULL,
  `DatFin` date NOT NULL,
  `DatObj` datetime NOT NULL,
  PRIMARY KEY (`Cle`)
) ENGINE=MyISAM AUTO_INCREMENT=937 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


CREATE TABLE `base_l` (
  `cl1` int(11) NOT NULL AUTO_INCREMENT,
  `clp` int(11) NOT NULL,
  `cl0` int(11) NOT NULL,
  `clL` int(11) NOT NULL,
  `PathNod` varchar(40) NOT NULL,
  `Chemin` varchar(43) NOT NULL,
  `Cle` int(11) NOT NULL,
  `TypL` varchar(12) NOT NULL,
  `Modul` int(11) NOT NULL,
  `Niv` smallint(6) NOT NULL,
  `Nivs` int(11) NOT NULL,
  `Ouv` tinyint(1) NOT NULL,
  `Rang` int(11) NOT NULL DEFAULT '999',
  `OptLien` int(11) NOT NULL,
  `TypLien` varchar(7) NOT NULL,
  `InfoLien` text NOT NULL,
  PRIMARY KEY (`cl1`),
  KEY `Cle` (`Cle`),
  KEY `clp` (`clp`)
) ENGINE=MyISAM AUTO_INCREMENT=970 DEFAULT CHARSET=latin1 AVG_ROW_LENGTH=46 ROW_FORMAT=DYNAMIC;


CREATE TABLE `base_l2` (
  `Codl2` int(11) NOT NULL AUTO_INCREMENT,
  `Cle` int(11) NOT NULL,
  `CodBl2` int(11) NOT NULL,
  `cl1` int(11) NOT NULL,
  `Typl2` varchar(6) NOT NULL,
  `bl2` int(11) NOT NULL,
  `Form` text NOT NULL,
  `Data` text NOT NULL,
  PRIMARY KEY (`Codl2`),
  KEY `Cle` (`Cle`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;


CREATE TABLE `bmem` (
  `CodMem` int(11) NOT NULL AUTO_INCREMENT,
  `CB` int(11) NOT NULL,
  `TypMem` varchar(6) NOT NULL,
  `ValMem` varchar(6) NOT NULL,
  `Lang` varchar(2) NOT NULL,
  `Memo` text NOT NULL,
  `VerMem` varchar(20) NOT NULL,
  `UserMem` varchar(15) NOT NULL,
  `TAMem` varchar(6) NOT NULL,
  `MajMem` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`CodMem`),
  KEY `CB` (`CB`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;


CREATE TABLE `calendrier` (
  `CodCal` int(11) NOT NULL AUTO_INCREMENT,
  `TypCal` varchar(6) NOT NULL,
  `DivCal` varchar(6) NOT NULL,
  `CodB` int(11) NOT NULL,
  `Quota` smallint(6) NOT NULL,
  `Langue` varchar(5) NOT NULL,
  `DatDeb` datetime NOT NULL,
  `DatFin` datetime NOT NULL,
  `Clot` tinyint(1) NOT NULL,
  `InfoCal` text NOT NULL,
  `MemoCal` text NOT NULL,
  `RowCal` text NOT NULL,
  PRIMARY KEY (`CodCal`),
  KEY `CodB` (`CodB`)
) ENGINE=MyISAM AUTO_INCREMENT=3358 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;


DELIMITER ;;

CREATE TRIGGER `delcal` BEFORE DELETE ON `calendrier` FOR EACH ROW
DELETE FROM cal_l WHERE CodCal = OLD. CodCal;;

DELIMITER ;

CREATE TABLE `cal_l` (
  `CodCalL` int(11) NOT NULL AUTO_INCREMENT,
  `CodCal` int(11) NOT NULL,
  `LCal` int(11) NOT NULL,
  `TypLCal` varchar(6) NOT NULL,
  `RefLCal` varchar(20) NOT NULL,
  `EtapLCal` varchar(6) NOT NULL,
  `RepLCal` date NOT NULL,
  `hebLCal` int(11) NOT NULL,
  `ChLCal` varchar(6) NOT NULL,
  `SaisieLCal` tinyint(4) NOT NULL,
  `CreatLCal` varchar(15) NOT NULL,
  `TriLCal` smallint(6) NOT NULL,
  `SelLCal` tinyint(4) NOT NULL DEFAULT '1',
  `JSLCal` text NOT NULL,
  `MemoLCal` text NOT NULL,
  `EnregLCal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `OldIns` int(11) NOT NULL,
  PRIMARY KEY (`CodCalL`),
  KEY `CodCo` (`LCal`),
  KEY `CodCal` (`CodCal`),
  KEY `hebLCal` (`hebLCal`)
) ENGINE=MyISAM AUTO_INCREMENT=132508 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


CREATE TABLE `client` (
  `CodCli` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Civil` varchar(6) NOT NULL,
  `Nom` varchar(30) NOT NULL,
  `Prenom` varchar(25) NOT NULL,
  `Rue` varchar(50) NOT NULL,
  `Adresse` text NOT NULL,
  `CP` varchar(8) NOT NULL,
  `Ville` varchar(35) NOT NULL,
  `Pays` varchar(20) NOT NULL,
  `Tel` varchar(20) NOT NULL,
  `Mobil` varchar(20) NOT NULL,
  `eMail` varchar(50) NOT NULL,
  `Societe` varchar(40) NOT NULL,
  `mpCli` varchar(15) NOT NULL,
  `MemoCli` text NOT NULL,
  `EnregCli` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`CodCli`)
) ENGINE=MyISAM AUTO_INCREMENT=484 DEFAULT CHARSET=latin1;


DELIMITER ;;

CREATE TRIGGER `delcli` BEFORE DELETE ON `client` FOR EACH ROW
DELETE FROM commande WHERE CodCli = OLD.CodCli;;

DELIMITER ;

CREATE TABLE `cogrp` (
  `idCoG` int(11) NOT NULL AUTO_INCREMENT,
  `cogNom` varchar(40) NOT NULL,
  `cogTyp` varchar(6) NOT NULL,
  `cogPerso` tinyint(4) NOT NULL,
  `cogQui` varchar(12) NOT NULL,
  `cogMemo` text NOT NULL,
  `cogEnreg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idCoG`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;


DELIMITER ;;

CREATE TRIGGER `delcogrp` BEFORE DELETE ON `cogrp` FOR EACH ROW
DELETE FROM cogrp_l WHERE coglGrp = OLD.idCoG;;

DELIMITER ;

CREATE TABLE `cogrp_l` (
  `idCoGl` int(11) NOT NULL AUTO_INCREMENT,
  `coglGrp` int(11) NOT NULL,
  `coglCo` int(11) NOT NULL,
  `Result` smallint(6) NOT NULL,
  `DatEnvoi` datetime NOT NULL,
  `Etat` varchar(10) NOT NULL,
  `Essai` smallint(6) NOT NULL,
  `ResTxt` text NOT NULL,
  PRIMARY KEY (`idCoGl`),
  UNIQUE KEY `index` (`coglGrp`,`coglCo`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;


CREATE TABLE `commande` (
  `CodCom` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `CodCli` int(10) unsigned NOT NULL,
  `RefCom` varchar(20) NOT NULL,
  `DatCom` date NOT NULL,
  `Montant` decimal(10,2) NOT NULL,
  `ModPaie` varchar(6) NOT NULL,
  `ModLiv` varchar(6) NOT NULL,
  `DatPaie` datetime NOT NULL,
  `ValidPaie` varchar(12) NOT NULL,
  `destLiv` char(6) NOT NULL,
  `AdLiv` text NOT NULL,
  `PaysLiv` varchar(2) NOT NULL,
  `TTC` decimal(10,2) NOT NULL,
  `TVA` decimal(10,2) NOT NULL,
  `Poids` decimal(10,2) NOT NULL,
  `Port` decimal(10,2) NOT NULL,
  `Promo` decimal(10,2) NOT NULL,
  `TextCmd` text NOT NULL,
  `MemoCmd` text NOT NULL,
  `DatLiv` date NOT NULL,
  `PaysIP` varchar(3) NOT NULL,
  `DatEnreg` datetime NOT NULL,
  PRIMARY KEY (`CodCom`),
  KEY `CodCli` (`CodCli`)
) ENGINE=MyISAM AUTO_INCREMENT=512 DEFAULT CHARSET=latin1;


DELIMITER ;;

CREATE TRIGGER `delcom` BEFORE DELETE ON `commande` FOR EACH ROW
DELETE FROM comprd WHERE CodCom = OLD.CodCom;;

DELIMITER ;

CREATE TABLE `comprd` (
  `CodComPrd` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `CodCom` int(10) unsigned NOT NULL,
  `CodPrd` int(10) unsigned NOT NULL,
  `Quant` smallint(5) unsigned NOT NULL,
  `Prix` decimal(10,2) NOT NULL,
  `Remise` int(11) NOT NULL,
  PRIMARY KEY (`CodComPrd`),
  KEY `CodCom` (`CodCom`,`CodPrd`)
) ENGINE=MyISAM AUTO_INCREMENT=938 DEFAULT CHARSET=latin1;


CREATE TABLE `contact` (
  `CodCo` int(11) NOT NULL AUTO_INCREMENT,
  `CodB` int(11) NOT NULL,
  `TypCo` tinyint(1) NOT NULL,
  `DivCo` varchar(6) NOT NULL,
  `SelCo` int(11) NOT NULL,
  `Ident` varchar(256) NOT NULL,
  `Civil` varchar(6) NOT NULL,
  `Civil2` varchar(9) NOT NULL,
  `Nom` varchar(30) NOT NULL,
  `Prenom` varchar(25) NOT NULL,
  `Adresse` text NOT NULL,
  `CP` varchar(8) NOT NULL,
  `Ville` varchar(35) NOT NULL,
  `Pays` varchar(20) NOT NULL,
  `Tel` varchar(20) NOT NULL,
  `Mobil` varchar(20) NOT NULL,
  `eMail` varchar(50) NOT NULL,
  `Societe` varchar(40) NOT NULL,
  `Profession` text NOT NULL,
  `mpCo` varchar(15) NOT NULL,
  `DatNaiss` date NOT NULL,
  `Zone` int(11) NOT NULL,
  `Libre` varchar(6) NOT NULL,
  `noLet` tinyint(1) NOT NULL,
  `RangCo` smallint(6) NOT NULL,
  `CreatCo` varchar(15) NOT NULL,
  `JSCo` text NOT NULL,
  `ImgCo` text NOT NULL,
  `MemoCo` text NOT NULL,
  `aboLet` tinyint(1) NOT NULL,
  `Particips` text NOT NULL,
  `CatCo` char(1) NOT NULL,
  `CatCo2` char(1) NOT NULL,
  `Csp` int(11) NOT NULL,
  `DatDem` date NOT NULL,
  `Infop` text NOT NULL,
  `Infolg` text NOT NULL,
  `EnregCo` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `TempCo` text NOT NULL,
  `password` varchar(60) NOT NULL,
  `Region` varchar(50) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `letOca` tinyint(4) NOT NULL COMMENT 'Lettre occasionnelle',
  `letPaper` tinyint(4) NOT NULL COMMENT 'Lettre annuelle papier',
  `letMail` tinyint(4) NOT NULL COMMENT 'Lettre annuelle par mail',
  `aut16` tinyint(4) NOT NULL,
  `datLetOca` datetime NOT NULL COMMENT 'Lettre occasionnelle',
  `datLetPaper` datetime NOT NULL COMMENT 'Lettre annuelle papier',
  `datLetMail` datetime NOT NULL COMMENT 'Lettre annuelle par mail',
  `datAut16` date NOT NULL,
  `roles` tinytext NOT NULL COMMENT '(DC2Type:json)',
  PRIMARY KEY (`CodCo`),
  KEY `CodB` (`CodB`)
) ENGINE=MyISAM AUTO_INCREMENT=39252 DEFAULT CHARSET=latin1;


DELIMITER ;;

CREATE TRIGGER `delco` BEFORE DELETE ON `contact` FOR EACH ROW
BEGIN
	DELETE FROM cal_l WHERE LCal = OLD.CodCo;
	DELETE FROM don WHERE DonCo = OLD.CodCo;
	DELETE FROM contact_l WHERE Col = OLD.CodCo;
	DELETE FROM contact_l WHERE ColP = OLD.CodCo AND ColT='famil';
	DELETE FROM cogrp_l WHERE coglCo = OLD.CodCo;
END;;

DELIMITER ;

CREATE TABLE `contact_l` (
  `CodCol` int(11) NOT NULL AUTO_INCREMENT,
  `Col` int(11) NOT NULL,
  `ColP` int(11) NOT NULL,
  `ColT` varchar(6) NOT NULL,
  `ColRel` int(11) NOT NULL,
  `ColTyp` varchar(6) NOT NULL,
  `JSCol` text NOT NULL,
  PRIMARY KEY (`CodCol`),
  KEY `CodCo` (`Col`,`ColP`),
  KEY `Col` (`Col`),
  KEY `ColP` (`ColP`)
) ENGINE=MyISAM AUTO_INCREMENT=15266 DEFAULT CHARSET=latin1;


CREATE TABLE `don` (
  `CodDon` int(11) NOT NULL AUTO_INCREMENT,
  `RefDon` varchar(9) NOT NULL,
  `DonCo` int(11) NOT NULL,
  `MntDon` decimal(10,2) NOT NULL,
  `MonDon` varchar(2) NOT NULL,
  `DestDon` varchar(6) NOT NULL,
  `BanqDon` int(11) NOT NULL,
  `ModDon` varchar(6) NOT NULL,
  `noDonR` int(11) NOT NULL,
  `ValidDon` tinyint(1) NOT NULL,
  `noRecu` tinyint(1) NOT NULL,
  `Adhesion` tinyint(1) NOT NULL,
  `MemoDon` text NOT NULL,
  `DatDon` datetime NOT NULL,
  `DatRecu` date NOT NULL,
  `TransDon` varchar(20) NOT NULL,
  `PaysDon` varchar(4) NOT NULL,
  `MsgBanq` varchar(20) NOT NULL,
  `EnregDon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `CreatDon` varchar(15) NOT NULL,
  `OldDon` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  PRIMARY KEY (`CodDon`),
  KEY `DonCo` (`DonCo`),
  KEY `BanqDon` (`BanqDon`)
) ENGINE=MyISAM AUTO_INCREMENT=57721 DEFAULT CHARSET=latin1;


CREATE TABLE `donr` (
  `CodDonR` int(11) NOT NULL AUTO_INCREMENT,
  `RefDon` varchar(9) NOT NULL,
  `DonRCo` int(11) NOT NULL,
  `DestDon` varchar(6) NOT NULL,
  `MntDon` decimal(10,2) NOT NULL,
  `MonDonR` varchar(2) NOT NULL,
  `BanqDon` int(11) NOT NULL,
  `Banque` varchar(45) NOT NULL,
  `ModDon` varchar(6) NOT NULL,
  `CpteBanq` varchar(40) NOT NULL,
  `AdBanq` text NOT NULL,
  `CPBanq` varchar(5) NOT NULL,
  `VilBanq` varchar(30) NOT NULL,
  `PaysBanq` varchar(20) NOT NULL,
  `DatVir` date NOT NULL,
  `VirFin` date NOT NULL,
  `VirFreq` varchar(1) NOT NULL,
  `DatTrans` date NOT NULL,
  `CreatDonR` varchar(15) NOT NULL,
  `EnregDonR` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(255) NOT NULL,
  PRIMARY KEY (`CodDonR`),
  KEY `DestDon` (`DestDon`),
  KEY `CodCo_l` (`DonRCo`),
  KEY `BanqDon` (`BanqDon`)
) ENGINE=MyISAM AUTO_INCREMENT=1218 DEFAULT CHARSET=latin1;


CREATE TABLE `donr_1` (
  `IdRetraitant` int(11) NOT NULL,
  `IdCompteBancaire` tinyint(4) NOT NULL,
  `Montant` decimal(10,2) NOT NULL,
  `Nb` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `donr_2` (
  `IdRetraitant` int(11) NOT NULL,
  `IdCompteBancaire` tinyint(4) NOT NULL,
  `Montant` decimal(10,2) NOT NULL,
  `Nb` tinyint(4) NOT NULL,
  `Date1` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `f_cf` (
  `idCF` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Commentaire Fondamental',
  `bCF` int(11) NOT NULL,
  `Appreciation` text NOT NULL,
  `Cassette` varchar(20) NOT NULL,
  `Refer` varchar(30) NOT NULL,
  `Tonalite` varchar(20) NOT NULL,
  `Caracteristique` varchar(15) NOT NULL,
  `Video` tinyint(4) NOT NULL,
  `Fichier` varchar(60) NOT NULL,
  `Theme` varchar(6) NOT NULL COMMENT 'La résurrection/La mort n''eixte pas/de l''exteriorité à l''intériorité + Dieu créateur créant + La vie dans l''Esprit Saint/Demeurer dans la parole/La nouvelle Alliance',
  `Predicateur` varchar(6) NOT NULL,
  `FichDeb` date NOT NULL,
  `FichFin` date NOT NULL,
  PRIMARY KEY (`idCF`),
  KEY `bCF` (`bCF`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `gift` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ref` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dest` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json_array)',
  `type` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mode` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codco` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `datvir` datetime DEFAULT NULL,
  `rAmount` int(11) DEFAULT NULL,
  `rDest` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `period` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `receipt` tinyint(1) DEFAULT NULL,
  `error` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `house` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_A47C990D146F3EA3` (`ref`)
) ENGINE=InnoDB AUTO_INCREMENT=205 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `groupe` (
  `idGrp` int(11) NOT NULL AUTO_INCREMENT,
  `LienG` int(11) NOT NULL,
  `CalG` int(11) NOT NULL,
  `CivilG` varchar(6) NOT NULL,
  `NomG` varchar(50) NOT NULL,
  `PrenomG` varchar(30) NOT NULL,
  `nbG` smallint(6) NOT NULL,
  `DebG` datetime NOT NULL,
  `FinG` datetime NOT NULL,
  `MemoG` text NOT NULL,
  PRIMARY KEY (`idGrp`)
) ENGINE=MyISAM AUTO_INCREMENT=179 DEFAULT CHARSET=latin1;


CREATE TABLE `hebcal` (
  `hcid` int(11) NOT NULL AUTO_INCREMENT,
  `hcHeb` int(11) NOT NULL,
  `hcCo` int(11) NOT NULL,
  `hcIns` int(11) NOT NULL,
  `hcTab` varchar(6) NOT NULL,
  `hcDeb` datetime NOT NULL,
  `hcFin` datetime NOT NULL,
  `hcValid` smallint(6) NOT NULL,
  PRIMARY KEY (`hcid`),
  KEY `hcCo` (`hcCo`),
  KEY `hcHeb` (`hcHeb`)
) ENGINE=MyISAM AUTO_INCREMENT=29076 DEFAULT CHARSET=latin1;


CREATE TABLE `hebergement` (
  `hid` int(11) NOT NULL AUTO_INCREMENT,
  `hLien` int(11) NOT NULL,
  `hTyp` varchar(6) NOT NULL,
  `hTyp2` varchar(6) NOT NULL,
  `hLib` varchar(50) NOT NULL,
  `hRef` varchar(20) NOT NULL,
  `hConf` int(11) NOT NULL,
  `hModel` int(11) NOT NULL,
  `hPlace` int(11) NOT NULL,
  `hNb` smallint(6) NOT NULL,
  `hh` decimal(6,2) NOT NULL,
  `hl` decimal(6,2) NOT NULL,
  `hw` decimal(6,2) NOT NULL,
  `hNiv` smallint(6) NOT NULL,
  `hPath` varchar(30) NOT NULL,
  `hRang` smallint(6) NOT NULL,
  `hAd` text NOT NULL,
  `hEnreg` text NOT NULL,
  `hMemo` text NOT NULL,
  PRIMARY KEY (`hid`)
) ENGINE=MyISAM AUTO_INCREMENT=174 DEFAULT CHARSET=latin1;


CREATE TABLE `hebtab` (
  `htid` int(11) NOT NULL AUTO_INCREMENT,
  `htHeb` int(11) NOT NULL,
  `htTab` int(11) NOT NULL,
  `htDom` varchar(6) NOT NULL,
  `htValN` int(11) NOT NULL,
  `htValT` varchar(6) NOT NULL,
  PRIMARY KEY (`htid`),
  KEY `htHeb` (`htHeb`),
  KEY `htTab` (`htTab`)
) ENGINE=MyISAM AUTO_INCREMENT=141 DEFAULT CHARSET=latin1;


CREATE TABLE `message` (
  `Mid` int(11) NOT NULL AUTO_INCREMENT,
  `MLien` int(11) NOT NULL,
  `MCod` int(11) NOT NULL,
  `MTyp` varchar(6) NOT NULL,
  `MTit` varchar(80) NOT NULL,
  `Msg` text NOT NULL,
  `MData` text NOT NULL,
  `MEnreg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Mid`),
  KEY `MLien` (`MLien`)
) ENGINE=MyISAM AUTO_INCREMENT=3045 DEFAULT CHARSET=latin1;


CREATE TABLE `message_l` (
  `Mlid` int(11) NOT NULL AUTO_INCREMENT,
  `MlMid` int(11) NOT NULL,
  `MlCo` int(11) NOT NULL,
  `MlTyp` varchar(6) NOT NULL,
  PRIMARY KEY (`Mlid`),
  KEY `MlCo` (`MlCo`),
  KEY `MlMid` (`MlMid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `participation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` double DEFAULT NULL,
  `created` datetime NOT NULL,
  `method` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `error` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trans` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `house` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=993 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `prodrub` (
  `CodRub` int(11) NOT NULL AUTO_INCREMENT,
  `Rubrique` varchar(60) NOT NULL,
  `ImgRub` text NOT NULL,
  `LangRub` varchar(2) NOT NULL,
  `rubHide` tinyint(4) NOT NULL,
  `MemoRub` text NOT NULL,
  PRIMARY KEY (`CodRub`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;


CREATE TABLE `produit` (
  `CodPrd` int(11) NOT NULL AUTO_INCREMENT,
  `RefPrd` varchar(20) NOT NULL,
  `Produit` varchar(80) NOT NULL,
  `CodRub` int(11) NOT NULL,
  `CodB` int(11) NOT NULL,
  `Isbn` char(13) NOT NULL,
  `Serie` varchar(50) NOT NULL,
  `Auteur` varchar(50) NOT NULL,
  `TypPrd` varchar(8) NOT NULL,
  `Annee` varchar(4) NOT NULL,
  `Prix` decimal(10,2) NOT NULL,
  `Promo` decimal(10,2) NOT NULL,
  `Poids` smallint(6) NOT NULL,
  `EtatPrd` varchar(6) NOT NULL,
  `Largeur` smallint(6) NOT NULL,
  `Hauteur` smallint(6) NOT NULL,
  `nbPage` smallint(6) NOT NULL,
  `Stock` smallint(6) NOT NULL,
  `Hide` tinyint(1) NOT NULL,
  `AdImg` text NOT NULL,
  `urlBook` text NOT NULL,
  `PagePrd` text NOT NULL,
  `MemoPrd` text NOT NULL,
  `Enreg` text NOT NULL,
  `Rang` smallint(6) NOT NULL,
  PRIMARY KEY (`CodPrd`),
  KEY `CodRub` (`CodRub`),
  KEY `CodB` (`CodB`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;


CREATE TABLE `projet` (
  `pjid` int(11) NOT NULL AUTO_INCREMENT,
  `CodB` int(11) NOT NULL,
  `pjEtat` int(11) NOT NULL,
  `pjPrio` smallint(6) NOT NULL,
  `pjDeb` datetime NOT NULL,
  `pjFin` datetime NOT NULL,
  `pjObj` datetime NOT NULL,
  `pjTps` int(6) NOT NULL,
  `pjFait` int(11) NOT NULL,
  `pjU` char(2) NOT NULL,
  PRIMARY KEY (`pjid`),
  KEY `CodB` (`CodB`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `tables` (
  `IDT` int(11) NOT NULL AUTO_INCREMENT,
  `TLien` int(11) NOT NULL,
  `TTyp` varchar(6) NOT NULL COMMENT 'Vide=Standard; LANG=Liaison traduction',
  `TRef` varchar(6) NOT NULL,
  `TLib` varchar(80) NOT NULL,
  `TNiv` smallint(6) NOT NULL,
  `TPath` varchar(40) NOT NULL,
  `TRang` smallint(6) NOT NULL,
  `TLang` varchar(3) NOT NULL,
  `TMemo` text NOT NULL,
  PRIMARY KEY (`IDT`)
) ENGINE=MyISAM AUTO_INCREMENT=174 DEFAULT CHARSET=latin1;


CREATE TABLE `tab_co` (
  `CodT` int(11) NOT NULL AUTO_INCREMENT,
  `CoT` int(11) NOT NULL,
  `LienT` int(11) NOT NULL,
  `NivT` int(11) NOT NULL,
  `dateT` datetime NOT NULL,
  `Typ1T` int(11) NOT NULL,
  `Typ2T` int(11) NOT NULL,
  `MemoT` text NOT NULL,
  PRIMARY KEY (`CodT`),
  KEY `LienT` (`LienT`),
  KEY `CoT` (`CoT`)
) ENGINE=MyISAM AUTO_INCREMENT=480 DEFAULT CHARSET=latin1;


CREATE TABLE `tcompte` (
  `idCpte` int(11) NOT NULL AUTO_INCREMENT,
  `Cpte` varchar(20) NOT NULL,
  `NomCP` varchar(80) NOT NULL,
  `NumCP` varchar(30) NOT NULL,
  `Banque` varchar(10) NOT NULL,
  UNIQUE KEY `idCpte` (`idCpte`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;


CREATE TABLE `tpays` (
  `CodPays` varchar(2) NOT NULL,
  `NomPays` varchar(30) NOT NULL,
  PRIMARY KEY (`CodPays`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `variable` (
  `codVar` int(11) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(40) NOT NULL,
  `Ident` varchar(15) NOT NULL,
  `CleN` int(11) NOT NULL,
  `Typ` varchar(8) NOT NULL,
  `Typ2` varchar(8) NOT NULL,
  `ValeurT` varchar(80) NOT NULL,
  `ValeurN` int(11) NOT NULL,
  `ValeurD` datetime NOT NULL,
  `ValeurB` tinyint(4) NOT NULL,
  `ValeurM` text NOT NULL,
  `DatMaj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`codVar`)
) ENGINE=MyISAM AUTO_INCREMENT=155 DEFAULT CHARSET=latin1;


-- 2019-12-04 09:04:46