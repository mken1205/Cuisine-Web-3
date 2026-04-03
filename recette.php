<?php
session_start();
require_once "config/db.php";
require_once "classes/Recette.php";
require_once "classes/Ingredient.php";
require_once "classes/Tag.php";

// Récupérer l'id depuis l'URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$recette = Recette::getById($pdo, $id);

// Si la recette n'existe pas, on redirige
if (!$recette) {
    header("Location: index.php");
    exit();
}

$ingredients = Ingredient::getByRecette($pdo, $id);
$tags  = Tag::getByRecette($pdo, $id);
$page_title  = $recette['titre'];

$emojis = ['🍕', '🥗', '🍰', '🍜', '🥘', '🍳', '🫕', '🥧'];
$emoji  = $emojis[$id % count($emojis)];
?>
<?php require_once "includes/header.php"; ?>

<a href="index.php" class="rp-back">← Retour aux recettes</a>

<div class="rp-hero">
    <div class="rp-hero-img">
        <?php if (!empty($recette['photo']) && file_exists("public/uploads/" . $recette['photo'])): ?>
            <img src="public/uploads/<?= htmlspecialchars($recette['photo']) ?>" alt="<?= htmlspecialchars($recette['titre']) ?>">
        <?php else: ?>
            <?= $emoji ?>
        <?php endif; ?>
    </div>
    <div class="rp-hero-meta">
        <div class="rp-label">Recette</div>
        <h1 class="rp-title"><?= htmlspecialchars($recette['titre']) ?></h1>
        <?php if (!empty($tags)): ?>
            <div class="rp-tags">
                <?php foreach ($tags as $t): ?>
                    <span class="rp-tag"><?= htmlspecialchars($t['nom']) ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="rp-date">Ajoutée le <?= date('d/m/Y', strtotime($recette['created_at'])) ?></div>
    </div>
</div>

<div class="rp-body">

    <!-- Colonne ingrédients -->
    <div class="rp-ingredients">
        <div class="rp-col-label">Ingrédients</div>
        <?php if (empty($ingredients)): ?>
            <p style="font-size:0.8rem; color:var(--text3)">Aucun ingrédient renseigné.</p>
        <?php else: ?>
            <?php foreach ($ingredients as $ing): ?>
                <div class="ing-item">
                    <span><?= htmlspecialchars($ing['nom']) ?></span>
                    <?php if (!empty($ing['quantite'])): ?>
                        <span class="ing-qty"><?= htmlspecialchars($ing['quantite']) ?></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Colonne préparation -->
    <div class="rp-steps">
        <h2 class="rp-steps-title">Préparation</h2>
        <p class="rp-description"><?= nl2br(htmlspecialchars($recette['description'])) ?></p>
    </div>

</div>

<?php require_once "includes/footer.php"; ?>
