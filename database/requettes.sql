USE livret_recettes; -- On indique à MySQL qu'on travaille dans cette base de données

-- ============================================
-- RECETTES
-- ============================================

-- getAllRecettes()
-- Récupère toutes les recettes de la base
SELECT * FROM recette; -- Le * signifie "toutes les colonnes" de la table recette

-- getRecetteById($id)
-- Récupère une seule recette grâce à son id
SELECT * FROM recette 
WHERE id = 1; -- On filtre pour n'avoir que la recette dont l'id est 1

-- getIngredientsByRecette($id)
-- Récupère tous les ingrédients d'une recette
-- On doit aller chercher dans 2 tables : ingredient et recette_ingredient
SELECT i.nom,       -- Le nom de l'ingrédient (ex: "Farine")
       i.image,     -- L'image de l'ingrédient
       ri.quantite  -- La quantité utilisée dans la recette (ex: "200g")
FROM ingredient i                                       -- On part de la table ingredient
JOIN recette_ingredient ri ON i.id = ri.ingredient_id  -- On la relie à recette_ingredient
WHERE ri.recette_id = 1;                               -- On filtre pour la recette n°1

-- getTagsByRecette($id)
-- Récupère tous les tags d'une recette
-- On doit aller chercher dans 2 tables : tag et recette_tag
SELECT t.nom        -- Le nom du tag (ex: "dessert", "hiver")
FROM tag t                                    -- On part de la table tag
JOIN recette_tag rt ON t.id = rt.tag_id       -- On la relie à recette_tag
WHERE rt.recette_id = 1;                      -- On filtre pour la recette n°1

-- searchByTitre($titre)
-- Recherche les recettes dont le titre contient un mot
SELECT * FROM recette
WHERE titre LIKE '%tarte%'; -- LIKE avec % = contient le mot "tarte" n'importe où dans le titre
                             -- ex: "Tarte aux pommes", "Mini tartes", "Tarte tatin"

-- searchByIngredient($nom)
-- Recherche les recettes qui contiennent un ingrédient
-- On doit traverser 3 tables : recette, recette_ingredient, ingredient
SELECT DISTINCT r.*          -- DISTINCT évite les doublons
FROM recette r               -- On part de la table recette
JOIN recette_ingredient ri   -- On la relie à recette_ingredient
    ON r.id = ri.recette_id  -- via l'id de la recette
JOIN ingredient i            -- On relie aussi la table ingredient
    ON i.id = ri.ingredient_id -- via l'id de l'ingrédient
WHERE i.nom LIKE '%farine%'; -- On filtre les recettes qui contiennent "farine"

-- searchByTag($nom)
-- Recherche les recettes qui ont un certain tag
-- On traverse 3 tables : recette, recette_tag, tag
SELECT DISTINCT r.*          -- DISTINCT évite les doublons
FROM recette r               -- On part de la table recette
JOIN recette_tag rt          -- On la relie à recette_tag
    ON r.id = rt.recette_id  -- via l'id de la recette
JOIN tag t                   -- On relie aussi la table tag
    ON t.id = rt.tag_id      -- via l'id du tag
WHERE t.nom LIKE '%dessert%'; -- On filtre les recettes qui ont le tag "dessert"

-- createRecette($titre, $description, $photo)
-- Ajoute une nouvelle recette dans la base
INSERT INTO recette (titre, description, photo)  -- On précise les colonnes à remplir
VALUES (
    'Quiche lorraine',          -- Le titre de la recette
    'Préchauffer le four...',   -- La description / étapes
    'uploads/quiche.jpg'        -- Le chemin vers la photo
);

-- updateRecette($id, $titre, $description, $photo)
-- Modifie une recette existante
UPDATE recette                              -- On indique la table à modifier
SET titre       = 'Quiche revisitée',      -- Nouvelle valeur pour le titre
    description = 'Nouvelle description',  -- Nouvelle valeur pour la description
    photo       = 'uploads/quiche2.jpg'    -- Nouvelle valeur pour la photo
WHERE id = 1;  -- IMPORTANT : sans WHERE on modifie TOUTES les recettes !

-- deleteRecette($id)
-- Supprime une recette de la base
-- Les liaisons recette_ingredient et recette_tag
-- sont supprimées automatiquement (ON DELETE CASCADE)
DELETE FROM recette
WHERE id = 1; -- IMPORTANT : sans WHERE on supprime TOUTES les recettes !

-- ============================================
-- INGREDIENTS
-- ============================================

-- getAllIngredients()
-- Récupère tous les ingrédients disponibles
SELECT * FROM ingredient; -- Retourne tous les ingrédients avec leur nom et image

-- createIngredient($nom, $image)
-- Ajoute un nouvel ingrédient
INSERT INTO ingredient (nom, image)
VALUES (
    'Chocolat',              -- Le nom de l'ingrédient
    'uploads/chocolat.jpg'   -- Le chemin vers son image
);

-- updateIngredient($id, $nom, $image)
-- Modifie un ingrédient existant
UPDATE ingredient
SET nom   = 'Chocolat noir',         -- Nouveau nom
    image = 'uploads/choco_noir.jpg' -- Nouvelle image
WHERE id = 1; -- On cible l'ingrédient n°1

-- addIngredientToRecette($recette_id, $ingredient_id, $quantite)
-- Lie un ingrédient à une recette avec sa quantité
INSERT INTO recette_ingredient (recette_id, ingredient_id, quantite)
VALUES (
    1,      -- L'id de la recette
    1,      -- L'id de l'ingrédient
    '200g'  -- La quantité utilisée
);

-- removeIngredientFromRecette($recette_id, $ingredient_id)
-- Supprime le lien entre un ingrédient et une recette
DELETE FROM recette_ingredient
WHERE recette_id    = 1  -- L'id de la recette
AND   ingredient_id = 1; -- L'id de l'ingrédient à retirer

-- ============================================
-- TAGS
-- ============================================

-- getAllTags()
-- Récupère tous les tags disponibles
SELECT * FROM tag; -- Retourne tous les tags existants

-- createTag($nom)
-- Ajoute un nouveau tag
INSERT INTO tag (nom)
VALUES ('rapide'); -- Le nom du nouveau tag

-- updateTag($id, $nom)
-- Modifie le nom d'un tag existant
UPDATE tag
SET nom = 'très rapide' -- Nouveau nom du tag
WHERE id = 1;           -- On cible le tag n°1

-- deleteTag($id)
-- Supprime un tag
-- ATTENTION : bloqué si le tag est encore utilisé par une recette (ON DELETE RESTRICT)
DELETE FROM tag
WHERE id = 1;

-- addTagToRecette($recette_id, $tag_id)
-- Lie un tag à une recette
INSERT INTO recette_tag (recette_id, tag_id)
VALUES (
    1, -- L'id de la recette
    1  -- L'id du tag
);

-- removeTagFromRecette($recette_id, $tag_id)
-- Supprime le lien entre un tag et une recette
DELETE FROM recette_tag
WHERE recette_id = 1  -- L'id de la recette
AND   tag_id     = 1; -- L'id du tag à retirer

-- ============================================
-- USER (admin)
-- ============================================

-- checkLogin($login, $password)
-- Vérifie les identifiants de l'administrateur
-- Le mot de passe sera comparé après hashage en PHP
SELECT * FROM user
WHERE login    = 'admin'      -- Le login saisi par l'utilisateur
AND   password = 'motdepasse'; -- Le mot de passe saisi (sera hashé en PHP)
