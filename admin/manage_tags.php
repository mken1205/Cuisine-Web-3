<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
require_once "../classes/Tag.php";

$page_title = "Tags";
$message    = '';
$erreur     = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['action_ajouter'])) {
        $nom = trim($_POST['nom'] ?? '');
        if ($nom === '') {
            $erreur = "Le nom est obligatoire.";
        } else {
            try {
                Tag::create($pdo, $nom);
                $message = "Tag \"$nom\" ajouté.";
            } catch (PDOException $e) {
                $erreur = "Ce tag existe déjà.";
            }
        }
    }

    if (isset($_POST['action_supprimer'])) {
        $id = (int)$_POST['suppr_id'];
        try {
            Tag::delete($pdo, $id);
            $message = "Tag supprimé.";
        } catch (PDOException $e) {
            $erreur = "Impossible de supprimer : ce tag est utilisé dans une recette.";
        }
    }
}

$tags = Tag::getAll($pdo);
require_once "header_admin.php";
?>

<h1 class="admin-title">Tags</h1>
<p class="admin-sub">Ajouter ou supprimer des tags pour classer les recettes.</p>

<?php if ($message !== ''): ?>
    <div class="flash flash-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<?php if ($erreur !== ''): ?>
    <div class="flash flash-error"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<!-- Formulaire d'ajout -->
<div class="form-section" style="max-width:500px;">
    <div class="form-section-title">Ajouter un tag</div>
    <form method="POST" action="manage_tags.php">
        <div class="form-group">
            <label class="form-label" for="nom">Nom du tag</label>
            <div style="display:flex; gap:0.75rem;">
                <input class="form-input" type="text" id="nom" name="nom"
                       placeholder="Ex: végétarien" required>
                <button type="submit" name="action_ajouter" class="btn btn-primary">Ajouter</button>
            </div>
        </div>
    </form>
</div>

<!-- Liste des tags -->
<h2 style="font-family:'Playfair Display',serif; font-weight:400; font-size:1.1rem; margin-bottom:1rem; color:var(--text2);">
    Liste (<?= count($tags) ?> tag(s))
</h2>

<?php if (empty($tags)): ?>
    <p style="font-size:0.85rem; color:var(--text3);">Aucun tag pour l'instant.</p>
<?php else: ?>
    <ul class="item-list" style="max-width:500px; border:1px solid var(--border);">
        <?php foreach ($tags as $t): ?>
            <li>
                <span><?= htmlspecialchars($t['nom']) ?></span>
                <form method="POST" action="manage_tags.php" style="display:inline;"
                      onsubmit="return confirm('Supprimer ce tag ?')">
                    <input type="hidden" name="suppr_id" value="<?= $t['id'] ?>">
                    <button type="submit" name="action_supprimer" class="item-del-btn">🗑️ Supprimer</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php require_once "footer_admin.php"; ?>
