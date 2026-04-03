<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
require_once "../classes/Recette.php";
require_once "../classes/Tag.php";

$page_title = "Gérer les recettes";
$message    = '';

// Suppression via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    // Supprimer l'image associée si elle existe
    $r = Recette::getById($pdo, $id);
    if ($r && !empty($r['photo']) && file_exists("../public/uploads/" . $r['photo'])) {
        unlink("../public/uploads/" . $r['photo']);
    }
    Recette::delete($pdo, $id);
    $message = "Recette supprimée.";
}

$recettes = Recette::getAll($pdo);

require_once "header_admin.php";
?>

<h1 class="admin-title">Gérer les recettes</h1>
<p class="admin-sub">Modifier ou supprimer les recettes existantes.</p>

<?php if ($message !== ''): ?>
    <div class="flash flash-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div style="margin-bottom:1.5rem;">
    <a href="add_recette.php" class="btn btn-primary">➕ Nouvelle recette</a>
</div>

<?php if (empty($recettes)): ?>
    <p style="color:var(--text3); font-size:0.85rem;">Aucune recette pour l'instant.</p>
<?php else: ?>
    <table class="recipe-table">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Tags</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recettes as $r): ?>
                <?php $tags = Tag::getByRecette($pdo, $r['id']); ?>
                <tr>
                    <td class="td-title"><?= htmlspecialchars($r['titre']) ?></td>
                    <td class="td-tags">
                        <?= implode(', ', array_map(fn($t) => htmlspecialchars($t['nom']), $tags)) ?>
                    </td>
                    <td><?= date('d/m/Y', strtotime($r['created_at'])) ?></td>
                    <td class="table-actions">
                        <a href="edit_recette.php?id=<?= $r['id'] ?>" class="tbl-btn">Modifier</a>
                        <a href="../recette.php?id=<?= $r['id'] ?>" class="tbl-btn" target="_blank">Voir</a>
                        <!-- Formulaire de suppression en ligne -->
                        <form method="POST" action="list_recettes.php" style="display:inline;"
                              onsubmit="return confirm('Supprimer définitivement cette recette ?')">
                            <input type="hidden" name="delete_id" value="<?= $r['id'] ?>">
                            <button type="submit" class="tbl-btn danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require_once "footer_admin.php"; ?>
