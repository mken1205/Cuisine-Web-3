<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' — ' : '' ?>Admin · Ghost Recipes</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<header class="masthead">
    <div class="masthead-top">
        <a href="../index.php" class="logo-wrap">
            <span class="logo-ghost">Ghost</span>
            <span class="logo-recipes">Recipes</span>
        </a>
        <nav class="masthead-links">
            <a href="../index.php">← Site public</a>
            <span style="font-size:0.7rem; color:var(--text3); letter-spacing:0.1em;">
                Connecté : <?= htmlspecialchars($_SESSION['login']) ?>
            </span>
        </nav>
    </div>
</header>

<div class="admin-layout">

<aside class="admin-sidebar">
    <span class="sidebar-label">Administration</span>
    <a href="dashboard.php"           class="sidebar-link <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php'           ? 'active' : '' ?>">
        Dashboard
    </a>
    <a href="add_recette.php"         class="sidebar-link <?= basename($_SERVER['PHP_SELF']) === 'add_recette.php'         ? 'active' : '' ?>">
        Ajouter recette
    </a>
    <a href="list_recettes.php"       class="sidebar-link <?= basename($_SERVER['PHP_SELF']) === 'list_recettes.php'       ? 'active' : '' ?>">
        Gérer recettes
    </a>
    <a href="manage_ingredients.php"  class="sidebar-link <?= basename($_SERVER['PHP_SELF']) === 'manage_ingredients.php'  ? 'active' : '' ?>">
        Ingrédients
    </a>
    <a href="manage_tags.php"         class="sidebar-link <?= basename($_SERVER['PHP_SELF']) === 'manage_tags.php'         ? 'active' : '' ?>">
        Tags
    </a>
    <a href="../logout.php" class="sidebar-logout">
        Se déconnecter
    </a>
</aside>

<main class="admin-main">
