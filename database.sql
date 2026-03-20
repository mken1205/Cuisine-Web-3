-- ============================================
-- Création de la base de données
-- ============================================
CREATE DATABASE IF NOT EXISTS livret_recettes; -- Crée la base seulement si elle n'existe pas déjà
USE livret_recettes;                          

-- ============================================
-- Table user
-- Stocke les informations de connexion
-- ============================================
CREATE TABLE user (
    id          INT AUTO_INCREMENT PRIMARY KEY, -- Numéro unique généré automatiquement
    login       VARCHAR(50) NOT NULL UNIQUE,    -- Nom de connexion, obligatoire et unique
    password    VARCHAR(255) NOT NULL,          -- Mot de passe hashé, obligatoire
    role        ENUM('admin', 'user')           -- Rôle : soit "admin" soit "user"
                DEFAULT 'user'                  -- Par défaut, tout nouveau compte est "user"
);

-- ============================================
-- Table recette
-- Stocke les informations de chaque recette
-- ============================================
CREATE TABLE recette (
    id          INT AUTO_INCREMENT PRIMARY KEY, -- Numéro unique généré automatiquement
    titre       VARCHAR(100) NOT NULL,          -- Nom de la recette, obligatoire (ex: "Tarte aux pommes")
    description TEXT NOT NULL,                 -- Étapes de préparation, obligatoire
    photo       VARCHAR(255),                  -- Chemin vers l'image (ex: "uploads/tarte.jpg"), facultatif
    created_at  DATETIME                       -- Date de création de la recette
                DEFAULT CURRENT_TIMESTAMP,     -- Rempli automatiquement à la création
    updated_at  DATETIME                       -- Date de dernière modification
                DEFAULT CURRENT_TIMESTAMP      
                ON UPDATE CURRENT_TIMESTAMP    -- Mis à jour automatiquement à chaque modification
);

-- ============================================
-- Table ingredient
-- Stocke les ingrédients disponibles
-- ============================================
CREATE TABLE ingredient (
    id          INT AUTO_INCREMENT PRIMARY KEY, -- Numéro unique généré automatiquement
    nom         VARCHAR(100) NOT NULL UNIQUE,   -- Nom de l'ingrédient, obligatoire et unique (ex: "Farine")
    image       VARCHAR(255)                    -- Chemin vers l'image de l'ingrédient, facultatif
);

-- ============================================
-- Table tag
-- Stocke les mots-clés pour classifier les recettes
-- ============================================
CREATE TABLE tag (
    id          INT AUTO_INCREMENT PRIMARY KEY, -- Numéro unique généré automatiquement
    nom         VARCHAR(50) NOT NULL UNIQUE     -- Nom du tag, obligatoire et unique (ex: "dessert", "hiver")
);

-- ============================================
-- Table de liaison recette_ingredient
-- Relie les recettes à leurs ingrédients
-- Une recette peut avoir plusieurs ingrédients
-- Un ingrédient peut apparaître dans plusieurs recettes
-- ============================================
CREATE TABLE recette_ingredient (
    recette_id      INT NOT NULL,               -- Référence vers la recette concernée
    ingredient_id   INT NOT NULL,               -- Référence vers l'ingrédient concerné
    quantite        VARCHAR(50),                -- Quantité de l'ingrédient (ex: "200g", "3 unités")
    PRIMARY KEY (recette_id, ingredient_id),    -- La combinaison des deux est unique
    FOREIGN KEY (recette_id)                    -- Vérifie que la recette existe bien
        REFERENCES recette(id)
        ON DELETE CASCADE,                      -- Si la recette est supprimée, la liaison est supprimée aussi
    FOREIGN KEY (ingredient_id)                 -- Vérifie que l'ingrédient existe bien
        REFERENCES ingredient(id)
        ON DELETE CASCADE                       -- Si l'ingrédient est supprimé, la liaison est supprimée aussi
);

-- ============================================
-- Table de liaison recette_tag
-- Relie les recettes à leurs tags
-- Une recette peut avoir plusieurs tags
-- Un tag peut apparaître sur plusieurs recettes
-- ============================================
CREATE TABLE recette_tag (
    recette_id  INT NOT NULL,                   -- Référence vers la recette concernée
    tag_id      INT NOT NULL,                   -- Référence vers le tag concerné
    PRIMARY KEY (recette_id, tag_id),           -- La combinaison des deux est unique
    FOREIGN KEY (recette_id)                    -- Vérifie que la recette existe bien
        REFERENCES recette(id)
        ON DELETE CASCADE,                      -- Si la recette est supprimée, la liaison est supprimée aussi
    FOREIGN KEY (tag_id)                        -- Vérifie que le tag existe bien
        REFERENCES tag(id)
        ON DELETE CASCADE                       -- Si le tag est supprimé, la liaison est supprimée aussi
);