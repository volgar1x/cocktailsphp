
-- Création des tables
-- Requêtes valables pour PostgreSQL
-- à adapter en cas d'utilisation de MySQL

CREATE TABLE Verre (
  ver_id SERIAL PRIMARY KEY,
  ver_type VARCHAR(30) NOT NULL
);

CREATE TABLE Ingredient (
  ing_id SERIAL PRIMARY KEY,
  ing_nom VARCHAR(30) NOT NULL
);

CREATE TABLE Boisson (
  boi_id SERIAL PRIMARY KEY,
  boi_nom VARCHAR(30) NOT NULL,
  boi_degreAlcool INT NOT NULL CHECK (boi_degreAlcool BETWEEN 0 AND 100)
);

CREATE TABLE Cocktail (
  coc_id SERIAL PRIMARY KEY,
  coc_nom VARCHAR(50) NOT NULL,
  coc_recette TEXT NOT NULL,
  coc_categorie VARCHAR(20) CHECK (coc_categorie IN ('short drink', 'long drink', 'after dinner')),
  ver_service INT REFERENCES Verre(ver_id),
  ver_preparation INT REFERENCES Verre(ver_id));

CREATE TABLE Compose1(
  boi_id INT REFERENCES Boisson(boi_id),
  coc_id INT REFERENCES Cocktail(coc_id),
  volume_cl INT NOT NULL,
  PRIMARY KEY (boi_id, coc_id)
);

CREATE TABLE Compose2(
  ing_id INT REFERENCES Ingredient(ing_id),
  coc_id INT REFERENCES Cocktail(coc_id),
  quantite INT NOT NULL,
  uniteMesure VARCHAR(10),
  PRIMARY KEY (ing_id, coc_id)
);

-- Insertion des données
INSERT INTO Boisson VALUES
  (DEFAULT, 'Rhum Blanc', 50),
  (DEFAULT, 'Rhum Ambré', 45),
  (DEFAULT, 'Vodka', 40),
  (DEFAULT, 'Cointreau', 40),
  (DEFAULT, 'Grand Marnier', 40),
  (DEFAULT, 'Cognac', 40),
  (DEFAULT, 'Whisky', 40),
  (DEFAULT, 'Calvados', 40),
  (DEFAULT, 'Téquila', 40),
  (DEFAULT, 'Benedictine', 40),
  (DEFAULT, 'Gin', 37),
  (DEFAULT, 'Curaçao Bleu', 30),
  (DEFAULT, 'Kahlua', 20),
  (DEFAULT, 'Liqueur de Cassis', 20),
  (DEFAULT, 'Liqueur de Violette', 20),
  (DEFAULT, 'Liqueur de Mure', 20),
  (DEFAULT, 'Liqueur de Pêche', 20),
  (DEFAULT, 'Liqueur de Framboise', 20),
  (DEFAULT, 'Liqueur de Whisky', 20),
  (DEFAULT, 'Vermouth rouge', 15),
  (DEFAULT, 'Vermouth blanc', 15),
  (DEFAULT, 'Vermouth dry', 15),
  (DEFAULT, 'Campari', 15),
  (DEFAULT, 'Picon', 15),
  (DEFAULT, 'Champagne', 12),
  (DEFAULT, 'Guinness', 5),
  (DEFAULT, 'Bière blonde', 5),
  (DEFAULT, 'Lait de coco', 0),
  (DEFAULT, 'Jus de citron', 0),
  (DEFAULT, 'Jus d''ananas', 0),
  (DEFAULT, 'Jus d''orange', 0),
  (DEFAULT, 'Jus de tomate', 0),
  (DEFAULT, 'Sirop de canne', 0),
  (DEFAULT, 'Sirop de grenadine', 0),
  (DEFAULT, 'Sirop de mangue', 0),
  (DEFAULT, 'Sirop de menthe', 0),
  (DEFAULT, 'Sirop d''orgeat', 0),
  (DEFAULT, 'Soda', 0),
  (DEFAULT, 'Ginger Ale', 0),
  (DEFAULT, 'Tonic', 0),
  (DEFAULT, 'Cola', 0),
  (DEFAULT, 'Orangina', 0),
  (DEFAULT, 'Crème fraiche', 0),
  (DEFAULT, 'Lait', 0),
  (DEFAULT, 'Jus de pomme', 0),
  (DEFAULT, 'Jus de cranberries', 0),
  (DEFAULT, 'Jus de mangue', 0),
  (DEFAULT, 'Jus de goyave', 0),
  (DEFAULT, 'Eau gazeuse', 0),
  (DEFAULT, 'Sirop ', 0),
  (DEFAULT, 'Get 27', 21),
  (DEFAULT, 'Get 31', 24),
  (DEFAULT, 'Armagnac', 40),
  (DEFAULT, 'Liqueur de Mandarine', 38),
  (DEFAULT, 'Limonade', 0),
  (DEFAULT, 'Pastis', 45),
  (DEFAULT, 'Mezcal', 40),
  (DEFAULT, 'Pineau', 18);

Insert INTO Ingredient VALUES
  (DEFAULT, 'Glaçon'),
  (DEFAULT, 'Feuille de menthe'),
  (DEFAULT, 'Olive verte'),
  (DEFAULT, 'Cerise'),
  (DEFAULT, 'Oeuf'),
  (DEFAULT, 'Jaune d''oeuf'),
  (DEFAULT, 'Blanc d''oeuf'),
  (DEFAULT, 'Sucre'),
  (DEFAULT, 'Citron'),
  (DEFAULT, 'Citron vert'),
  (DEFAULT, 'Orange'),
  (DEFAULT, 'Sel');


INSERT INTO Verre VALUES
  (DEFAULT, 'Old fashioned'), (DEFAULT, 'Tumbler'), (DEFAULT, 'Shaker'),
  (DEFAULT, 'Mixer'), (DEFAULT,'Flûte');

-- Russe Blanc
INSERT INTO Cocktail VALUES (
 DEFAULT, 'Russe Blanc', 'Placer les glaçons dans le verre...', 'short drink',
 (SELECT ver_id FROM Verre WHERE LOWER(ver_type) = 'old fashioned'),
 (SELECT ver_id FROM Verre WHERE LOWER(ver_type) = 'shaker'));

INSERT INTO Compose1 VALUES
 ((SELECT boi_id FROM Boisson WHERE boi_nom = 'Vodka'), currval('cocktail_coc_id_seq'), 5),
 ((SELECT boi_id FROM Boisson WHERE boi_nom = 'Kahlua'), currval('cocktail_coc_id_seq'), 2),
 ((SELECT boi_id FROM Boisson WHERE boi_nom = 'Crème fraiche'), (SELECT coc_id FROM cocktail WHERE coc_nom = 'Russe Blanc'),3);

INSERT INTO Compose2 VALUES
 ((SELECT ing_id FROM Ingredient WHERE ing_nom = 'Glaçon'), (SELECT coc_id FROM cocktail WHERE coc_nom = 'Russe Blanc'), 3);


-- requêtes

--3.1 Identifiant des cocktails contenant de la vodka (avec une sous-requête).

SELECT coc_id FROM compose1 WHERE boi_id = (SELECT boi_id FROM Boisson WHERE boi_nom = 'Vodka');

--3.2 Identifiant des cocktails contenant de la vodka (avec une jointure).

SELECT coc_id FROM compose1, boisson
WHERE compose1.boi_id = boisson.boi_id AND boi_nom = 'Vodka';

--3.3 Noms des cocktails contenant de la Vodka (triés dans l'ordre alphabétique).

SELECT coc_nom FROM Cocktail WHERE coc_id IN (req.2) ORDER BY coc_nom;

--3.4 Nombre de cocktails contenant la boisson numéro 1.

SELECT COUNT(*) FROM compose1 WHERE boi_id = 1;

--3.5 Nombre de cocktails disponibles pour chaque identifiant de boisson.

SELECT boi_id, COUNT(coc_id) FROM compose1 GROUP BY boi_id;

--3.6-- Liste des noms de cocktail et de leur volume.

SELECT coc_nom, SUM(volume_cl)
FROM cocktail c NATURAL JOIN compose1 c1
GROUP BY coc_nom;

--3.7 Liste des boissons utilisées dans aucun cocktail.

SELECT * FROM boisson WHERE boi_id NOT IN (SELECT boi_id FROM compose1);

--3.8 Degré d'alcool moyen des boissons composant chaque cocktail
SELECT c.coc_nom, avg(boi_degreAlcool)  as degreMoyen
FROM cocktail c, compose1 c1, Boisson b
WHERE c.coc_id=c1.coc_id AND c1.boi_id =b.boi_id
GROUP BY c.coc_nom;

--3.9 nombre de cocktail sans alcool
-- SELECT COUNT(*) FROM (req8) as t
-- WHERE t.degreMoyen = 0;

-- Pina Colada
INSERT INTO Cocktail VALUES (
 DEFAULT, 'Pina Colada', 'Dans un mixer, verser les ingrédients avec 5 ou 6 glaçons et mixer le tout', 'long drink',
 (SELECT ver_id FROM Verre WHERE LOWER(ver_type) = 'Tumbler'),
 (SELECT ver_id FROM Verre WHERE LOWER(ver_type) = 'Mixer'));

INSERT INTO Compose1 VALUES
 ((SELECT boi_id FROM Boisson WHERE boi_nom = 'Rhum Blanc'), currval('cocktail_coc_id_seq'), 4),
 ((SELECT boi_id FROM Boisson WHERE boi_nom = 'Rhum Ambré'), currval('cocktail_coc_id_seq'), 2),
 ((SELECT boi_id FROM Boisson WHERE boi_nom = 'Lait de coco'), currval('cocktail_coc_id_seq'), 2),
 ((SELECT boi_id FROM Boisson WHERE boi_nom = 'Jus d''ananas'), currval('cocktail_coc_id_seq'), 12);

INSERT INTO Compose2 VALUES
 ((SELECT ing_id FROM Ingredient WHERE ing_nom = 'Glaçon'), (SELECT coc_id FROM cocktail WHERE coc_nom = 'Pina Colada'), 5);

-- Red Lion
-- Margarita
-- Side Car
-- Rusty Nail
-- Pink Lady
-- 'Ti punch
-- White Lady
-- Picon bière
-- Planteur
