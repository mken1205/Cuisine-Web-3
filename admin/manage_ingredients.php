<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
require_once "../classes/Ingredient.php";

$page_title = "Ingrédients";
$message    = '';
$erreur     = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['action_ajouter'])) {
        $nom = trim($_POST['nom'] ?? '');
        if ($nom === '') {
            $erreur = "Le nom est obligatoire.";
        } else {
            try {
                Ingredient::create($pdo, $nom);
                $message = "Ingrédient \"$nom\" ajouté.";
            } catch (PDOException $e) {
                $erreur = "Cet ingrédient existe déjà.";
            }
        }
    }

    if (isset($_POST['action_supprimer'])) {
        $id = (int)$_POST['suppr_id'];
        try {
            Ingredient::delete($pdo, $id);
            $message = "Ingrédient supprimé.";
        } catch (PDOException $e) {
            $erreur = "Impossible de supprimer : cet ingrédient est utilisé dans une recette.";
        }
    }
}

$ingredients = Ingredient::getAll($pdo);
require_once "header_admin.php";
?>

<h1 class="admin-title">Ingrédients</h1>
<p class="admin-sub">Ajouter ou supprimer des ingrédients disponibles pour les recettes.</p>

<?php if ($message !== ''): ?>
    <div class="flash flash-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<?php if ($erreur !== ''): ?>
    <div class="flash flash-error"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<!-- Formulaire d'ajout -->
<div class="form-section" style="max-width:500px;">
    <div class="form-section-title">Ajouter un ingrédient</div>
    <form method="POST" action="manage_ingredients.php">
        <div class="form-group">
            <label class="form-label" for="nom">Nom de l'ingrédient</label>
            <div style="display:flex; gap:0.75rem;">
                <input class="form-input" type="text" id="nom" name="nom"
                       placeholder="Ex: Basilic" required>
                <button type="submit" name="action_ajouter" class="btn btn-primary">Ajouter</button>
            </div>
        </div>
    </form>
</div>

<!-- Liste des ingrédients -->
<h2 style="font-family:'Playfair Display',serif; font-weight:400; font-size:1.1rem; margin-bottom:1rem; color:var(--text2);">
    Liste (<?= count($ingredients) ?> ingrédient(s))
</h2>

<?php if (empty($ingredients)): ?>
    <p style="font-size:0.85rem; color:var(--text3);">Aucun ingrédient pour l'instant.</p>
<?php else: ?>
    <ul class="item-list" style="max-width:500px; border:1px solid var(--border);">
        <?php foreach ($ingredients as $ing): ?>
            <li>
                <span><?= htmlspecialchars($ing['nom']) ?></span>
                <form method="POST" action="manage_ingredients.php" style="display:inline;"
                      onsubmit="return confirm('Supprimer cet ingrédient ?')">
                    <input type="hidden" name="suppr_id" value="<?= $ing['id'] ?>">
                    <button type="submit" name="action_supprimer" class="item-del-btn">🗑️ Supprimer</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php require_once "footer_admin.php"; ?>
