<?php
// Calcule automatiquement le chemin de base du projet en gardant notre $base = "/cuisine_final/"
// le but c'est par example les liens CSS ett fonctionnent dans admin/dashboard.php 
// donc jutilise base pour pour tous les liens internes à ton projet : pages , formulaires , fichiers : CSS , JS , Images ett


// j ai utiliser Script_Name qui est une valeur automatique par php qui change toute seule selon la page ouverte
$script = $_SERVER['SCRIPT_NAME']?? '';               // comme securite si la variable existe pas il donne chaîne vide


if (strpos($script, '/admin/') !== false) {           // j'utilise strpos pour chercher "/admin/" dans l’URL 
    $base = rtrim(dirname(dirname($script)), '/') . '/';   // On est dans /admin/ donc on remonte d'un niveau en utilisant dirname(dirname ($script))
} else {
    $base = rtrim(dirname($script), '/') . '/';       // sinon j'enlève le fichier avec dirname($script)    
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- je securise contre XSS attaque -->
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' — ' : '' ?>Ghost Recipes</title>  
    <link rel="stylesheet" href="<?= $base ?>public/css/style.css">   <!-- ici on adapte notre base-->  
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<header class="masthead">
    <div class="masthead-top">
        <a href="<?= $base ?>index.php" class="logo-wrap">
            <span class="logo-ghost">Ghost</span>
            <span class="logo-recipes">Recipes</span>
        </a>

        <!-- pour la barre de recherche on doit l'envoyer comme formulaire vers la bonne page peu importe où on est grace a base sinon erreur : fichie n'existe pas-->
        <form class="search-wrap" method="GET" action="<?= $base ?>recherche.php">
            <span class="search-icon"></span>
            <input type="text" name="q" placeholder="Rechercher une recette..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        </form>

        <nav class="masthead-links">
            <a href="<?= $base ?>index.php">Accueil</a>
            <a href="<?= $base ?>recherche.php">Recettes</a>

            <!-- savoir si l’utilisateur est connecté et Sans ça notr login / admin ne marche pas -->
            <?php if (isset($_SESSION['user_id'])): ?>   
                <a href="<?= $base ?>admin/dashboard.php">Admin</a> 
            <?php else: ?>
                <a href="<?= $base ?>login.php">Connexion</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
