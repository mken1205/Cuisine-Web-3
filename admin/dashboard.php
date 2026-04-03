<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
require_once "../classes/Recette.php";
require_once "../classes/Ingredient.php";
require_once "../classes/Tag.php";

$page_title = "Dashboard";

// Statistiques
$nb_recettes    = count(Recette::getAll($pdo));
$nb_ingredients = count(Ingredient::getAll($pdo));
$nb_tags        = count(Tag::getAll($pdo));

// 3 dernières recettes
$stmt = $pdo->query("SELECT * FROM recette ORDER BY created_at DESC LIMIT 3");
$dernieres = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once "header_admin.php";
?>

<h1 class="admin-title">Dashboard</h1>
<p class="admin-sub">Bienvenue, <?= htmlspecialchars($_SESSION['login']) ?> — Vue d'ensemble</p>

<!-- Statistiques -->
<div class="stat-cards">
    <div class="stat-card">
        <div class="stat-card-label">Recettes</div>
        <div class="stat-card-val"><?= $nb_recettes ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label">Ingrédients</div>
        <div class="stat-card-val"><?= $nb_ingredients ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label">Tags</div>
        <div class="stat-card-val"><?= $nb_tags ?></div>
    </div>
</div>

<!-- Dernières recettes ajoutées -->
<h2 style="font-family:'Playfair Display',serif; font-weight:400; font-size:1.1rem; margin-bottom:1rem; color:var(--text2);">
    Dernières recettes
</h2>
<table class="recipe-table">
    <thead>
        <tr>
            <th>Titre</th>
            <th>Ajoutée le</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dernieres as $r): ?>
        <tr>
            <td class="td-title"><?= htmlspecialchars($r['titre']) ?></td>
            <td><?= date('d/m/Y', strtotime($r['created_at'])) ?></td>
            <td class="table-actions">
                <a href="edit_recette.php?id=<?= $r['id'] ?>" class="tbl-btn">Modifier</a>
                <a href="../recette.php?id=<?= $r['id'] ?>" class="tbl-btn" target="_blank">Voir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once "footer_admin.php"; ?>
