<?php
require_once("../includes/auth.php");
require_once("../classes/Recette.php");
require_once("../config/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $desc = $_POST['description'];
    Recette::create($pdo, $titre, $desc, null); 
    echo "Recette ajoutée !";
}
?>

<form method="POST">
    <input type="text" name="titre" placeholder="Titre" required><br><br>
    <textarea name="description" placeholder="Description" required></textarea><br><br>
    <button type="submit">Ajouter</button>
</form>