-- ============================================
-- Base de données : livret_recettes
-- ============================================
CREATE DATABASE IF NOT EXISTS livret_recettes;
USE livret_recettes;

-- Désactiver les clés étrangères pour les DROP
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS recette_tag;
DROP TABLE IF EXISTS recette_ingredient;
DROP TABLE IF EXISTS recette;
DROP TABLE IF EXISTS ingredient;
DROP TABLE IF EXISTS tag;
DROP TABLE IF EXISTS user;
SET FOREIGN_KEY_CHECKS = 1;

-- Table user
CREATE TABLE user (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    login    VARCHAR(50)  NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,          -- mot de passe hashé avec password_hash()
    role     ENUM('admin','user') DEFAULT 'user'
);

-- Table recette
CREATE TABLE recette (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    titre       VARCHAR(100) NOT NULL,
    description TEXT         NOT NULL,
    photo       VARCHAR(255),                -- nom du fichier image (ex: tarte.jpg)
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table ingredient
CREATE TABLE ingredient (
    id  INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE
);

-- Table tag
CREATE TABLE tag (
    id  INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE
);

-- Table de liaison recette <-> ingredient
CREATE TABLE recette_ingredient (
    recette_id    INT         NOT NULL,
    ingredient_id INT         NOT NULL,
    quantite      VARCHAR(50),              -- ex: "200g", "3 unités"
    PRIMARY KEY (recette_id, ingredient_id),
    FOREIGN KEY (recette_id)    REFERENCES recette(id)    ON DELETE CASCADE,
    FOREIGN KEY (ingredient_id) REFERENCES ingredient(id) ON DELETE RESTRICT
);

-- Table de liaison recette <-> tag
CREATE TABLE recette_tag (
    recette_id INT NOT NULL,
    tag_id     INT NOT NULL,
    PRIMARY KEY (recette_id, tag_id),
    FOREIGN KEY (recette_id) REFERENCES recette(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id)     REFERENCES tag(id)     ON DELETE RESTRICT
);

-- ============================================
-- Données de test
-- ============================================

-- Utilisateur admin (mot de passe: admin123)
INSERT INTO user (login, password, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- Note: hash généré avec password_hash('admin123', PASSWORD_DEFAULT)
-- À changer en production !

-- Tags
INSERT INTO tag (nom) VALUES
('dessert'), ('entrée'), ('plat principal'),
('végétarien'), ('rapide'), ('au four'), ('hiver'), ('été');

-- Ingrédients
INSERT INTO ingredient (nom) VALUES
('Farine'), ('Oeuf'), ('Beurre'), ('Sucre'), ('Pomme'),
('Lait'), ('Sel'), ('Poivre'), ('Tomate'), ('Mozzarella'),
('Lardon'), ('Crème fraîche'), ('Chocolat'), ('Levure');

-- Recettes
INSERT INTO recette (titre, description, photo) VALUES
('Tarte aux pommes',
 'Préchauffer le four à 180°C. Étaler la pâte dans un moule. Éplucher et couper les pommes en lamelles. Les disposer sur la pâte. Saupoudrer de sucre. Enfourner 35 minutes jusqu''à ce que la tarte soit dorée.',
 NULL),
('Quiche lorraine',
 'Préchauffer le four à 200°C. Étaler la pâte dans un moule. Mélanger les oeufs, la crème fraîche et les lardons dans un bol. Saler et poivrer. Verser le mélange sur la pâte. Enfourner 30 minutes.',
 NULL),
('Pizza margherita',
 'Mélanger farine, levure, sel et eau tiède. Pétrir 10 minutes puis laisser reposer 1h. Étaler la pâte, garnir de coulis de tomate et de mozzarella. Cuire au four à 220°C pendant 15 minutes.',
 NULL),
('Mousse au chocolat',
 'Faire fondre le chocolat avec le beurre au bain-marie. Séparer les blancs des jaunes. Incorporer les jaunes au chocolat refroidi. Monter les blancs en neige ferme. Incorporer délicatement les blancs au chocolat. Réfrigérer 2h.',
 NULL);

-- Liaisons recette - ingrédient
INSERT INTO recette_ingredient (recette_id, ingredient_id, quantite) VALUES
(1, 1, '200g'), (1, 2, '2'), (1, 3, '100g'), (1, 4, '50g'), (1, 5, '3'),
(2, 1, '150g'), (2, 2, '3'), (2, 12, '20cl'), (2, 11, '200g'), (2, 7, '1 pincée'),
(3, 1, '300g'), (3, 14, '1 sachet'), (3, 9, '2'), (3, 10, '125g'), (3, 7, '1 pincée'),
(4, 13, '200g'), (4, 2, '4'), (4, 3, '50g'), (4, 4, '30g');

-- Liaisons recette - tag
INSERT INTO recette_tag (recette_id, tag_id) VALUES
(1, 1), (1, 6), (1, 7),   -- tarte: dessert, au four, hiver
(2, 2), (2, 6), (2, 5),   -- quiche: entrée, au four, rapide
(3, 3), (3, 6), (3, 4),   -- pizza: plat principal, au four, végétarien
(4, 1), (4, 5);            -- mousse: dessert, rapide
