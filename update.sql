-- 27/02/2020 - Mise à jour des types de produit existants : [livre, livreaud, livrepar, cd] en remplacement de [book, other]
UPDATE produit SET TypPrd = 'livre' WHERE TypPrd = 'book';
UPDATE produit SET TypPrd = '' WHERE TypPrd = 'other';
UPDATE regles_taxes SET TypPrd = '' WHERE TypPrd = 'autre';

-- 23/10/2020 MaJ des tables liées aux éditions
-- TVA en France pour les livres, livrets de partitions, livres audio et CD
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE - FRANCE', 5.50, 'FR', 'livre');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE AUDIO - FRANCE', 5.50, 'FR', 'livreaud');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE PART - FRANCE', 5.50, 'FR', 'livrepar');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('CD - FRANCE', 20.00, 'FR', 'cd');

-- TVA en Corse pour les livres, livrets de partitions, livres audio et CD
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE - CORSE', 2.10, 'CS', 'livre');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE AUDIO - CORSE', 2.10, 'CS', 'livreaud');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE PART - CORSE', 2.10, 'CS', 'livrepar');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('CD - CORSE', 20.00, 'CS', 'cd');

-- TVA en Guadeloupe/Martinique/Réunion pour les livres, livrets de partitions, livres audio et CD
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE - DOMTOM', 2.10, 'RE,MQ,GP', 'livre');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE AUDIO - DOMTOM', 2.10, 'RE,MQ,GP', 'livreaud');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE PART - DOMTOM', 2.10, 'RE,MQ,GP', 'livrepar');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('CD - DOMTOM', 8.50, 'RE,MQ,GP', 'cd');

-- TVA des pays UE pour les livres
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE - EU', 10.00, 'AT,CZ,FI,SK', 'livre');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE - EU', 0.00, 'BE,UK,IE', 'livre');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE - EU', 20.00, 'BG', 'livre');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE - EU', 5.00, 'CY,HR,HU,MT,PL', 'livre');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE - EU', 7.00, 'DE', 'livre');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE - EU', 25.00, 'DK', 'livre');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE - EU', 9.00, 'EE,LT,NL', 'livre');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE - EU', 6.00, 'EL,PT,SE', 'livre');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE - EU', 4.00, 'ES,IT', 'livre');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE - EU', 3.00, 'LU', 'livre');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE - EU', 12.00, 'LV', 'livre');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE - EU', 9.50, 'SI', 'livre');

-- TVA des pays UE pour les livrets de partitions et livres audio
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE AUDIO - EU', 20.00, 'AT,BG,EE', 'livreaud');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE PART - EU', 20.00, 'AT,BG,EE', 'livrepar');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE AUDIO - EU', 21.00, 'BE,CZ,LT,LV', 'livreaud');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE PART - EU', 21.00, 'BE,CZ,LT,LV', 'livrepar');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE AUDIO - EU', 19.00, 'CY', 'livreaud');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE PART - EU', 19.00, 'CY', 'livrepar');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE AUDIO - EU', 7.00, 'DE', 'livreaud');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE PART - EU', 7.00, 'DE', 'livrepar');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE AUDIO - EU', 25.00, 'DK', 'livreaud');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE PART - EU', 25.00, 'DK', 'livrepar');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE AUDIO - EU', 24.00, 'EL,FI', 'livreaud');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE PART - EU', 24.00, 'EL,FI', 'livrepar');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE AUDIO - EU', 4.00, 'ES,IT', 'livreaud');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE PART - EU', 4.00, 'ES,IT', 'livrepar');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE AUDIO - EU', 5.00, 'HR,HU,MT,PL,RO', 'livreaud');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE PART - EU', 5.00, 'HR,HU,MT,PL,RO', 'livrepar');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE AUDIO - EU', 23.00, 'IE', 'livreaud');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE PART - EU', 23.00, 'IE', 'livrepar');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE AUDIO - EU', 3.00, 'LU', 'livreaud');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE PART - EU', 3.00, 'LU', 'livrepar');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE AUDIO - EU', 9.00, 'NL', 'livreaud');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE PART - EU', 9.00, 'NL', 'livrepar');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE AUDIO - EU', 6.00, 'PT,SE', 'livreaud');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE PART - EU', 6.00, 'PT,SE', 'livrepar');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE AUDIO - EU', 9.50, 'SI', 'livreaud');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE PART - EU', 9.50, 'SI', 'livrepar');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE AUDIO - EU', 10.00, 'SK', 'livreaud');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE PART - EU', 10.00, 'SK', 'livrepar');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE AUDIO - EU', 0.00, 'UK', 'livreaud');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('LIVRE PART - EU', 0.00, 'UK', 'livrepar');

-- TVA des pays UE pour les CD
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('CD - EU', 20.00, 'AT,BG,EE,SK,UK', 'cd');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('CD - EU', 21.00, 'BE,CZ,ES,LT,LV,NL', 'cd');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('CD - EU', 19.00, 'CY,DE,RO', 'cd');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('CD - EU', 25.00, 'DK,HR,SE', 'cd');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('CD - EU', 24.00, 'EL,FI', 'cd');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('CD - EU', 27.00, 'HU', 'cd');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('CD - EU', 23.00, 'IE,PL,PT', 'cd');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('CD - EU', 22.00, 'IT,SI', 'cd');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('CD - EU', 17.00, 'LU', 'cd');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('CD - EU', 18.00, 'MT', 'cd');
INSERT INTO regles_taxes (LibTax, Taux, Pays, TypPrd) VALUES ('CD - EU', 18.00, 'MT', 'cd');

-- Activation des pays disponibles à la livraison
UPDATE tpays SET DispLiv = 1, minLiv = 5, maxLiv = 7, CodPaysPBX = 'FRA', CodPaysPaypal = 'fr_FR' WHERE CodPays IN ('RE', 'GP', 'MQ', 'YT', 'GY', 'PM', 'PF', 'WF', 'NC', 'TF');
INSERT INTO tpays (CodPays, NomPays, CodPaysPBX, CodPaysPaypal, CodPostaux, MinLiv, MaxLiv, DispLiv) VALUES ('MF', 'Saint-Martin', 'FRA', 'fr_FR', '', 5, 7, 1);
INSERT INTO tpays (CodPays, NomPays, CodPaysPBX, CodPaysPaypal, CodPostaux, MinLiv, MaxLiv, DispLiv) VALUES ('BL', 'Saint-Barthélemy', 'FRA', 'fr_FR', '', 5, 7, 1);

-- Passage de tous les pays non francophones en anglais
UPDATE tpays SET DispLiv = 1, minLiv = 3, maxLiv = 8, CodPaysPBX = 'GBR', CodPaysPaypal = 'en_US' WHERE CodPays IN ('AT','BE','BG','CY','CZ','DE','DK','EE','EL','ES','FI','HR','HU','IE','IT','LT','LU','LV','MT','NL','PL','PT','RO','SE','SI','SK','UK');

INSERT INTO transport (LibPort, Poids, Prix, Pays) VALUES
('Roche', 0, 0.00, ''),
('Font', 0, 0.00, ''),
('Hors France', 500, 12.55, ''),
('Hors France', 1000, 15.50, ''),
('Hors France', 2000, 17.55, ''),
('Hors France', 5000, 22.45, ''),
('Hors France', 10000, 37.00, ''),
('Hors France', 30000, 61.50, ''),
('France', 250, 4.95, 'FR,CS'),
('France', 500, 6.35, 'FR,CS'),
('France', 750, 7.25, 'FR,CS'),
('France', 1000, 7.95, 'FR,CS'),
('France', 2000, 8.95, 'FR,CS'),
('France', 5000, 13.75, 'FR,CS'),
('France', 10000, 20.05, 'FR,CS'),
('France', 30000, 28.55, 'FR,CS'),
('OM 1', 500, 9.60, 'RE,GP,MQ,YT,GY,PM,MF,BL'),
('OM 1', 1000, 14.60, 'RE,GP,MQ,YT,GY,PM,MF,BL'),
('OM 1', 2000, 19.90, 'RE,GP,MQ,YT,GY,PM,MF,BL'),
('OM 1', 5000, 29.90, 'RE,GP,MQ,YT,GY,PM,MF,BL'),
('OM 1', 10000, 47.90, 'RE,GP,MQ,YT,GY,PM,MF,BL'),
('OM 1', 30000, 106.90, 'RE,GP,MQ,YT,GY,PM,MF,BL'),
('OM 2', 500, 11.65, 'PF,WF,NC,TF'),
('OM 2', 1000, 17.45, 'PF,WF,NC,TF'),
('OM 2', 2000, 30.60, 'PF,WF,NC,TF'),
('OM 2', 5000, 51.50, 'PF,WF,NC,TF'),
('OM 2', 10000, 100.50, 'PF,WF,NC,TF'),
('OM 2', 30000, 245.50, 'PF,WF,NC,TF');

-- Modification de l'URL de la page "Les nouveautés" en "Nos sélections" dans toutes les langues
UPDATE page SET SubTitle = 'Nos Sélections', Description = 'Editions nos sélections', ImmutableId = 'editions-nos-selections' WHERE id = 36;
UPDATE page SET SubTitle = 'Our Selections', Description = 'Editions our selections', ImmutableId = 'editions-nos-selections' WHERE id = 69;
UPDATE page SET SubTitle = 'Unsere Auswahl', Description = 'Verlag unsere Auswahl', ImmutableId = 'editions-nos-selections' WHERE id = 100;
UPDATE page SET SubTitle = 'Nuestras selecciones', Description = 'Ediciones nuestras selecciones', ImmutableId = 'editions-nos-selections' WHERE id = 127;
UPDATE page SET SubTitle = 'Le nostre selezioni', Description = 'Edizioni le nostre selezioni', ImmutableId = 'editions-nos-selections' WHERE id = 154;

UPDATE orm_routes SET staticPrefix = '/fr/editions-nos-selections', name = 'editions-nos-selections' WHERE id = 41;
UPDATE orm_routes SET staticPrefix = '/en/publications-news', name = 'editions-our-selections' WHERE id = 71;
UPDATE orm_routes SET staticPrefix = '/de/publikationen-neu', name = 'verlag-unsere-auswahl' WHERE id = 101;
UPDATE orm_routes SET staticPrefix = '/es/publicaciones-nuevo', name = 'ediciones-nuestras-selecciones' WHERE id = 128;
UPDATE orm_routes SET staticPrefix = '/it/pubblicazioni-nuovo', name = 'edizioni-le-nostre-selezioni' WHERE id = 155;

-- Modification de l'URL espagnol de "Dons ponctuels"
UPDATE orm_routes SET staticPrefix = '/es/donacion-de-una-sola-vez', name = 'donacion-de-una-sola-vez' WHERE staticPrefix = '/es/donacin-de-una-sola-vez';

-- Modification de plusieurs URLs (20/07/21)
UPDATE orm_routes SET name = 'en-los-origenes', staticPrefix = '/es/en-los-origenes' WHERE name = 'fundadores';
UPDATE orm_routes SET name = 'zu-den-ursprungen', staticPrefix = '/de/zu-den-ursprungen' WHERE name = 'grunder';
UPDATE orm_routes SET name = 'alle-origini', staticPrefix = '/it/alle-origini' WHERE name = 'fondatori';
UPDATE orm_routes SET name = 'the-beginning', staticPrefix = '/en/the-beginning' WHERE name = 'founders';
UPDATE orm_routes SET name = 'los-conferenciantes', staticPrefix = '/es/los-conferenciantes' WHERE name = 'altavoces';
UPDATE orm_routes SET name = 'participar-en-un-retiro', staticPrefix = '/es/participar-en-un-retiro' WHERE name = 'venir-de-retiro';
UPDATE orm_routes SET name = 'les-fontanilles-es', staticPrefix = '/es/les-fontanilles-es' WHERE name = 'fontanilles-es';
UPDATE orm_routes SET name = 'informaciones-practicas-de-la-roche-d-or', staticPrefix = '/es/informaciones-practicas-de-la-roche-d-or' WHERE name = 'informaciones-utiles-de-la-roche-dor';
UPDATE orm_routes SET name = 'informaciones-practicas-de-fontanilles', staticPrefix = '/es/informaciones-practicas-de-fontanilles' WHERE name = 'informaciones-utiles-de-fontanilles';
UPDATE orm_routes SET name = 'nuestros-enlaces', staticPrefix = '/es/nuestros-enlaces' WHERE name = 'enlaces-de-interes';
UPDATE orm_routes SET name = 'otras-formas-de-donacion', staticPrefix = '/es/otras-formas-de-donacion' WHERE name = 'donacion';
UPDATE orm_routes SET name = 'donaciones-puntuales-o-regulares', staticPrefix = '/es/donaciones-puntuales-o-regulares' WHERE name = 'donacion-de-una-sola-vez';
UPDATE orm_routes SET name = 'inscripcion', staticPrefix = '/es/inscripcion' WHERE name = 'registro-jubilado';