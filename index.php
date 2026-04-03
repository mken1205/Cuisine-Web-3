<?php
session_start();
require_once "config/db.php";
require_once "classes/Recette.php";
require_once "classes/Tag.php";

$page_title = "Accueil";
$recettes   = Recette::getAll($pdo);   // ici j'appele la methode getall()de la classe Recette

// Emoji par défaut selon l'index de la recette a afficher si y a pas d image ajoutee
$emojis = ['🍕', '🥗', '🍰', '🍜', '🥘', '🍳', '🫕', '🥧'];

// Header
?>
<?php require_once "includes/header.php"; ?>

<!-- Partie 01 : accueillir l’utilisateu et le formulaire de recherche sur une recette-->
<section class="hero">
    <h1 class="hero-title">Bienvenue sur <em>Ghost Recipes</em></h1>
    <p class="hero-sub"> Un petit coin gourmand pour explorer, tester et savourer des recettes qui vous font sourire !</p>

    <form class="hero-search" method="GET" action="recherche.php">
        <input type="text" name="q" placeholder="Chercher une recette...">
        <button type="submit">Chercher</button>
    </form>


</section>

<!-- Partie 02 : Affichage des cartes de recettes -->

<div class="section-head">
    <h2 class="section-title">Toutes les recettes</h2>
    <a href="recherche.php" class="section-link">Voir tout →</a>
</div>

<div class="card-grid">
    <?php if (empty($recettes)): ?>              <!-- je verifie si y'a pas de recettes %-->
        <div class="empty-state">Aucune recette pour l'instant.</div>
    <?php else: ?>
        <!--Ici on affiche les 4 premieres recettes -->
        <?php 
            //php foreach ($recettes as $i => $r):
            foreach (array_slice($recettes, 0, 4) as $i => $r):  
            ?>
            <?php $tags = Tag::getByRecette($pdo, $r['id']); ?>  
            <a href="recette.php?id=<?= $r['id'] ?>" class="recipe-card">

                <!-- ici on verifie s'il y a une photo de recette existe dans uploads sinon on on mets un emoji -->
                <div class="card-thumb">
                    <?php if (!empty($r['photo']) && file_exists("public/uploads/" . $r['photo'])): ?>
                        <img src="public/uploads/<?= htmlspecialchars($r['photo']) ?>" alt="<?= htmlspecialchars($r['titre']) ?>">
                    <?php else: ?>
                        <?= $emojis[$i % count($emojis)] ?>  <!-- count sert a mettre les emojies selon les index et meme répéter les emojis si plus de 8 recettes-->
                    <?php endif; ?>
                </div>

                <!-- la j affiche les nom des tags -->
                <div class="card-tags">
                    <?php foreach ($tags as $t): ?>
                        <span class="card-tag"><?= htmlspecialchars($t['nom']) ?></span>
                    <?php endforeach; ?>
                </div>
                <!-- la j affiche le titre et descrition-->
                <h3 class="card-title"><?= htmlspecialchars($r['titre']) ?></h3>
                <p class="card-desc"><?= htmlspecialchars($r['description']) ?></p>

                 <!-- la j affiche la date et le lien vers la page de la recette (voir)-->
                <div class="card-footer">
                    <span class="card-date"><?= date('d/m/Y', strtotime($r['created_at'])) ?></span>
                    <span class="card-more">Voir →</span>
                </div>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

 <!-- Footer-->
<?php require_once "includes/footer.php"; ?>
