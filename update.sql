-- 27/02/2020 - Mise à jour des types de produit existants : [livre, livreaud, livrepar, cd] en remplacement de [book, other]
UPDATE produit SET TypPrd = 'livre' WHERE TypPrd = 'book';
UPDATE produit SET TypPrd = '' WHERE TypPrd = 'other';
UPDATE regles_taxes SET TypPrd = '' WHERE TypPrd = 'autre';