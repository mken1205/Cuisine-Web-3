<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
require_once "../classes/Recette.php";
require_once "../classes/Ingredient.php";
require_once "../classes/Tag.php";

// Nos variables : titre a aficher dans le header et admin , message de succes apres l ajout , erreur si le formulaire est mal rempli ou si l’upload échoue
$page_title = "Ajouter une recette";
$message  = '';
$erreur  = '';

// on verifie d'abord si le fromulaire est a été soumis via POST et puis tt le traitement se mis dans ce bloc
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Partie 01 : infos generales 
    $titre   = trim($_POST['titre']       ?? '');              //j'ai utiliser trim()pour effacer les espaces avant et apres le texte
    $description = trim($_POST['description'] ?? '');
    $photo = null;     // sera chargee par admin

    if ($titre === '' || $description === '') {
        $erreur = "Le titre et la description sont obligatoires.";
    } else {
        // Gestion de l'upload de photo 
        // Vérifie si un fichier a été envoyé = pas vide puis je Récupère son extention et la met en minuscules
        if (!empty($_FILES['photo']['name'])) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

            // pour sercuriser contre les upoloads dangereux comme malwars, backdoors ett je definis les extentions autorises
            $autorises = ['jpg', 'jpeg', 'png'];
            if (in_array($ext, $autorises)) {
                $nom_fichier = uniqid('recette_') . '.' . $ext;       // ici c'st essentiel de genere un nom unique pour éviter d’écraser une autre photo a laide de uniqid()
                move_uploaded_file($_FILES['photo']['tmp_name'], "../public/uploads/" . $nom_fichier);
                $photo = $nom_fichier;
            } else {
                $erreur = "Format d'image non autorisé (jpg, jpeg, png).";
            }
        }

        if ($erreur === '') {
            // Créer la recette
            $recette_id = Recette::create($pdo, $titre, $description, $photo);

            // Ajouter les ingrédients cochés avec leurs quantités
            if (!empty($_POST['ingredients'])) {
                foreach ($_POST['ingredients'] as $ing_id) {
                    $quantite = trim($_POST['quantite'][$ing_id] ?? '');
                    Recette::addIngredient($pdo, $recette_id, (int)$ing_id, $quantite);
                }
            }

            // Ajouter les tags cochés
            if (!empty($_POST['tags'])) {
                foreach ($_POST['tags'] as $tag_id) {
                    Recette::addTag($pdo, $recette_id, (int)$tag_id);
                }
            }

            $message = "Recette ajoutée avec succès !";
        }
    }
}

$tous_ingredients = Ingredient::getAll($pdo);
$tous_tags        = Tag::getAll($pdo);

require_once "header_admin.php";
?>

<h1 class="admin-title">Ajouter une recette</h1>
<p class="admin-sub">Remplir le formulaire pour créer une nouvelle recette.</p>

<?php if ($message !== ''): ?>
    <div class="flash flash-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<?php if ($erreur !== ''): ?>
    <div class="flash flash-error"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<form method="POST" action="add_recette.php" enctype="multipart/form-data">

    <div class="form-section">
        <div class="form-section-title">Informations générales</div>
        <div class="form-grid">
            <div class="form-group full">
                <label class="form-label" for="titre">Titre *</label>
                <input class="form-input" type="text" id="titre" name="titre"
                       value="<?= htmlspecialchars($_POST['titre'] ?? '') ?>" required placeholder="Ex: Tarte aux pommes">
            </div>
            <div class="form-group full">
                <label class="form-label" for="description">Description / Étapes de préparation *</label>
                <textarea class="form-textarea" id="description" name="description" rows="6" required
                          placeholder="Décrivez les étapes de préparation..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>
            <div class="form-group full">
                <label class="form-label" for="photo">Photo (optionnel)</label>
                <input class="form-input" type="file" id="photo" name="photo" accept="image/*">
            </div>
        </div>
    </div>

    <div class="form-section">
        <div class="form-section-title">Ingrédients</div>
        <?php if (empty($tous_ingredients)): ?>
            <p style="font-size:0.82rem; color:var(--text3)">
                Aucun ingrédient disponible.
                <a href="manage_ingredients.php" style="color:var(--gold)">Ajouter des ingrédients →</a>
            </p>
        <?php else: ?>
            <div class="checkboxes-grid">
                <?php foreach ($tous_ingredients as $ing): ?>
                    <div class="checkbox-item">
                        <div class="checkbox-with-qty">
                            <input type="checkbox" name="ingredients[]"
                                   value="<?= $ing['id'] ?>"
                                   id="ing_<?= $ing['id'] ?>"
                                   <?= in_array($ing['id'], $_POST['ingredients'] ?? []) ? 'checked' : '' ?>>
                            <label for="ing_<?= $ing['id'] ?>"><?= htmlspecialchars($ing['nom']) ?></label>
                            <input type="text" name="quantite[<?= $ing['id'] ?>]"
                                   placeholder="qté"
                                   value="<?= htmlspecialchars($_POST['quantite'][$ing['id']] ?? '') ?>">
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="form-section">
        <div class="form-section-title">Tags</div>
        <?php if (empty($tous_tags)): ?>
            <p style="font-size:0.82rem; color:var(--text3)">
                Aucun tag disponible.
                <a href="manage_tags.php" style="color:var(--gold)">Ajouter des tags →</a>
            </p>
        <?php else: ?>
            <div class="checkboxes-grid">
                <?php foreach ($tous_tags as $tag): ?>
                    <div class="checkbox-item">
                        <input type="checkbox" name="tags[]"
                               value="<?= $tag['id'] ?>"
                               id="tag_<?= $tag['id'] ?>"
                               <?= in_array($tag['id'], $_POST['tags'] ?? []) ? 'checked' : '' ?>>
                        <label for="tag_<?= $tag['id'] ?>"><?= htmlspecialchars($tag['nom']) ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="btn-row">
        <button type="submit" class="btn btn-primary">➕ Ajouter la recette</button>
        <a href="dashboard.php" class="btn btn-ghost">Annuler</a>
    </div>

</form>

<?php require_once "footer_admin.php"; ?>
