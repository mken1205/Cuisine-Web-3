<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
require_once "../classes/Recette.php";
require_once "../classes/Ingredient.php";
require_once "../classes/Tag.php";

$page_title = "Modifier une recette";
$message    = '';
$erreur     = '';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$recette = Recette::getById($pdo, $id);

if (!$recette) {
    header("Location: list_recettes.php");
    exit();
}

// Tags et ingrédients actuels de la recette
$tags_actuels = array_column(Tag::getByRecette($pdo, $id), 'id');
$ings_actuels = Ingredient::getByRecette($pdo, $id);
// Transformer en tableau id => quantite pour afficher les quantités dans le formulaire
$ings_actuels_map = [];
foreach ($ings_actuels as $ing) {
    $ings_actuels_map[$ing['id']] = $ing['quantite'];
}

$tous_ingredients = Ingredient::getAll($pdo);
$tous_tags        = Tag::getAll($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre       = trim($_POST['titre']       ?? '');
    $description = trim($_POST['description'] ?? '');
    $photo       = $_POST['photo_actuelle']   ?? null;

    if ($titre === '' || $description === '') {
        $erreur = "Le titre et la description sont obligatoires.";
    } else {
        // Nouvelle photo si uploadée
        if (!empty($_FILES['photo']['name'])) {
            $ext      = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $autorises = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $autorises)) {
                // Supprimer l'ancienne photo
                if ($photo && file_exists("../public/uploads/" . $photo)) {
                    unlink("../public/uploads/" . $photo);
                }
                $nom_fichier = uniqid('recette_') . '.' . $ext;
                move_uploaded_file($_FILES['photo']['tmp_name'], "../public/uploads/" . $nom_fichier);
                $photo = $nom_fichier;
            } else {
                $erreur = "Format d'image non autorisé.";
            }
        }

        if ($erreur === '') {
            Recette::update($pdo, $id, $titre, $description, $photo);

            // Remettre les ingrédients à zéro puis réinsérer
            Recette::clearIngredients($pdo, $id);
            if (!empty($_POST['ingredients'])) {
                foreach ($_POST['ingredients'] as $ing_id) {
                    $quantite = trim($_POST['quantite'][$ing_id] ?? '');
                    Recette::addIngredient($pdo, $id, (int)$ing_id, $quantite);
                }
            }

            // Remettre les tags à zéro puis réinsérer
            Recette::clearTags($pdo, $id);
            if (!empty($_POST['tags'])) {
                foreach ($_POST['tags'] as $tag_id) {
                    Recette::addTag($pdo, $id, (int)$tag_id);
                }
            }

            $message = "Recette modifiée avec succès !";
            // Recharger les données
            $recette = Recette::getById($pdo, $id);
            $tags_actuels = array_column(Tag::getByRecette($pdo, $id), 'id');
            $ings_actuels_map = [];
            foreach (Ingredient::getByRecette($pdo, $id) as $ing) {
                $ings_actuels_map[$ing['id']] = $ing['quantite'];
            }
        }
    }
}

require_once "header_admin.php";
?>

<h1 class="admin-title">Modifier une recette</h1>
<p class="admin-sub">Modification de : <strong><?= htmlspecialchars($recette['titre']) ?></strong></p>

<?php if ($message !== ''): ?>
    <div class="flash flash-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<?php if ($erreur !== ''): ?>
    <div class="flash flash-error"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<form method="POST" action="edit_recette.php?id=<?= $id ?>" enctype="multipart/form-data">
    <input type="hidden" name="photo_actuelle" value="<?= htmlspecialchars($recette['photo'] ?? '') ?>">

    <div class="form-section">
        <div class="form-section-title">Informations générales</div>
        <div class="form-grid">
            <div class="form-group full">
                <label class="form-label" for="titre">Titre *</label>
                <input class="form-input" type="text" id="titre" name="titre"
                       value="<?= htmlspecialchars($recette['titre']) ?>" required>
            </div>
            <div class="form-group full">
                <label class="form-label" for="description">Description / Étapes *</label>
                <textarea class="form-textarea" id="description" name="description" rows="6" required><?= htmlspecialchars($recette['description']) ?></textarea>
            </div>
            <div class="form-group full">
                <label class="form-label" for="photo">Nouvelle photo (optionnel)</label>
                <?php if (!empty($recette['photo'])): ?>
                    <p style="font-size:0.75rem; color:var(--text3); margin-bottom:0.4rem;">
                        Photo actuelle : <?= htmlspecialchars($recette['photo']) ?>
                    </p>
                <?php endif; ?>
                <input class="form-input" type="file" id="photo" name="photo" accept="image/*">
            </div>
        </div>
    </div>

    <div class="form-section">
        <div class="form-section-title">Ingrédients</div>
        <div class="checkboxes-grid">
            <?php foreach ($tous_ingredients as $ing): ?>
                <div class="checkbox-item">
                    <div class="checkbox-with-qty">
                        <input type="checkbox" name="ingredients[]"
                               value="<?= $ing['id'] ?>"
                               id="ing_<?= $ing['id'] ?>"
                               <?= isset($ings_actuels_map[$ing['id']]) ? 'checked' : '' ?>>
                        <label for="ing_<?= $ing['id'] ?>"><?= htmlspecialchars($ing['nom']) ?></label>
                        <input type="text" name="quantite[<?= $ing['id'] ?>]"
                               placeholder="qté"
                               value="<?= htmlspecialchars($ings_actuels_map[$ing['id']] ?? '') ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="form-section">
        <div class="form-section-title">Tags</div>
        <div class="checkboxes-grid">
            <?php foreach ($tous_tags as $tag): ?>
                <div class="checkbox-item">
                    <input type="checkbox" name="tags[]"
                           value="<?= $tag['id'] ?>"
                           id="tag_<?= $tag['id'] ?>"
                           <?= in_array($tag['id'], $tags_actuels) ? 'checked' : '' ?>>
                    <label for="tag_<?= $tag['id'] ?>"><?= htmlspecialchars($tag['nom']) ?></label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="btn-row">
        <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
        <a href="list_recettes.php" class="btn btn-ghost">Annuler</a>
        <a href="../recette.php?id=<?= $id ?>" class="btn btn-ghost" target="_blank">Voir la recette →</a>
    </div>

</form>

<?php require_once "footer_admin.php"; ?>
