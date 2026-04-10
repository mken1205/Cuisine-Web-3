<?php
session_start();
require_once "config/db.php";
require_once "classes/Recette.php";
require_once "classes/Tag.php";

$page_title = "Recettes";

// Recherche par mot-clé
$q = trim($_GET['q'] ?? '');

// Filtrage par tag
$tag_id = isset($_GET['tag']) ? (int)$_GET['tag'] : 0;

if ($tag_id > 0) {
    // Recettes ayant ce tag
    $stmt = $pdo->prepare("
        SELECT r.* FROM recette r
        JOIN recette_tag rt ON r.id = rt.recette_id
        WHERE rt.tag_id = :tid
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([':tid' => $tag_id]);
    $recettes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} elseif ($q !== '') {
    $recettes = Recette::rechercher($pdo, $q);
} else {
    $recettes = Recette::getAll($pdo);
}

$tous_tags = Tag::getAll($pdo);
$emojis    = ['🍕', '🥗', '🍰', '🍜', '🥘', '🍳', '🫕', '🥧'];
?>
<script src="public/js/recherche.js"></script>
<?php require_once "includes/header.php"; ?>

<div class="recherche-wrap">

    <h1 class="recherche-titre">
        <?php if ($q !== ''): ?>
            Résultats pour "<?= htmlspecialchars($q) ?>"
        <?php elseif ($tag_id > 0): ?>
            Recettes par tag
        <?php else: ?>
            Toutes les recettes
        <?php endif; ?>
    </h1>
    <p class="recherche-info">
        <strong><?= count($recettes) ?></strong> recette(s) trouvée(s)
    </p>

    <!-- Filtres par tag -->
    <div class="tags-filtre">
        <a href="recherche.php" class="tag-btn <?= ($tag_id === 0 && $q === '') ? 'active' : '' ?>">Tout</a>
        <?php foreach ($tous_tags as $t): ?>
            <a href="recherche.php?tag=<?= $t['id'] ?>"
               class="tag-btn <?= ($tag_id === $t['id']) ? 'active' : '' ?>">
                <?= htmlspecialchars($t['nom']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Grille de résultats -->
    <div class="card-grid">
        <?php if (empty($recettes)): ?>
            <div class="empty-state">Aucune recette ne correspond à votre recherche.</div>
        <?php else: ?>
            <?php foreach ($recettes as $i => $r): ?>
                <?php $tags = Tag::getByRecette($pdo, $r['id']); ?>
                <a href="recette.php?id=<?= $r['id'] ?>" class="recipe-card">
                    <div class="card-thumb">
                        <?php if (!empty($r['photo']) && file_exists("public/uploads/" . $r['photo'])): ?>
                            <img src="public/uploads/<?= htmlspecialchars($r['photo']) ?>" alt="">
                        <?php else: ?>
                            <?= $emojis[$r['id'] % count($emojis)] ?>
                        <?php endif; ?>
                    </div>
                    <div class="card-tags">
                        <?php foreach ($tags as $t): ?>
                            <span class="card-tag"><?= htmlspecialchars($t['nom']) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <h3 class="card-title"><?= htmlspecialchars($r['titre']) ?></h3>
                    <p class="card-desc"><?= htmlspecialchars($r['description']) ?></p>
                    <div class="card-footer">
                        <span class="card-date"><?= date('d/m/Y', strtotime($r['created_at'])) ?></span>
                        <span class="card-more">Voir →</span>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>
<script src="public/js/recherche.js"></script>
<?php require_once "includes/footer.php"; ?>
