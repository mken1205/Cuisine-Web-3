# Ghost Recipes — Livret de Recettes

Projet PHP/MySQL — 2ème année Informatique

---

## Installation

### 1. Importer la base de données

Dans phpMyAdmin ou via la ligne de commande :
```bash
mysql -u root -p < database.sql
```

Cela crée la base `livret_recettes` avec les tables et les données de test.

### 2. Configurer la connexion

Modifier `config/db.php` si nécessaire :
```php
$host   = "localhost";
$dbname = "livret_recettes";
$user   = "root";
$pass   = "";         // votre mot de passe MySQL
```

### 3. Placer le projet dans le serveur web

- **XAMPP** : copier dans `htdocs/cuisine/`
- **WAMP**  : copier dans `www/cuisine/`

Accès : `http://localhost/cuisine/`

### 4. Connexion admin

- URL : `http://localhost/cuisine/login.php`
- Login : `admin`
- Mot de passe : `admin123`




## Structure du projet

```
cuisine/
├── database.sql              ← Script SQL complet (structure + données)
├── config/
│   └── db.php                ← Connexion PDO à la base
├── classes/
│   ├── Element.php           ← Classe parent (héritage)
│   ├── Recette.php           ← Classe Recette (hérite Element)
│   ├── Ingredient.php        ← Classe Ingredient (hérite Element)
│   └── Tag.php               ← Classe Tag (hérite Element)
├── includes/
│   ├── auth.php              ← Protection pages admin (session)
│   ├── header.php            ← En-tête pages publiques
│   └── footer.php            ← Pied de page
├── admin/
│   ├── header_admin.php      ← En-tête + sidebar admin
│   ├── footer_admin.php      ← Pied de page admin
│   ├── dashboard.php         ← Tableau de bord
│   ├── add_recette.php       ← Ajouter une recette
│   ├── list_recettes.php     ← Liste + supprimer
│   ├── edit_recette.php      ← Modifier une recette
│   ├── manage_ingredients.php← Gérer les ingrédients
│   └── manage_tags.php       ← Gérer les tags
├── public/
│   ├── css/style.css         ← Feuille de style unique
│   ├── uploads/              ← Photos uploadées par l'admin
│   └── images/               ← Images statiques
├── index.php                 ← Page d'accueil
├── recette.php               ← Détail d'une recette
├── recherche.php             ← Recherche + filtre par tag
├── login.php                 ← Connexion admin
└── logout.php                ← Déconnexion
```

---

## Fonctionnalités

**Site public :**
- Accueil avec liste des recettes
- Page détail d'une recette (ingrédients + préparation)
- Recherche par mot-clé
- Filtre par tag

**Espace admin (connexion requise) :**
- Dashboard avec statistiques
- Ajouter / modifier / supprimer des recettes
- Upload de photo pour les recettes
- Gérer les ingrédients (ajouter / supprimer)
- Gérer les tags (ajouter / supprimer)

---

## Concepts PHP illustrés

- **Héritage** : `Recette`, `Ingredient`, `Tag` héritent de `Element`
- **Polymorphisme** : méthode `afficher()` surchargée dans chaque classe
- **PDO** : connexion et requêtes préparées (protection contre les injections SQL)
- **Sessions** : authentification admin avec `$_SESSION`
- **Formulaires** : méthode POST avec validation et gestion des fichiers
