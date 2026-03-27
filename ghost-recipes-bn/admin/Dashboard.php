<?php
require_once("../includes/auth.php"); 

echo "<h1>Admin Dashboard</h1>";
echo "<a href='add_recette.php'>Ajouter une recette</a><br>";
echo "<a href='edit_recette.php'>Modifier une recette</a><br>";
echo "<a href='delete_recette.php'>Supprimer une recette</a><br>";
echo "<a href='../logout.php'>Se déconnecter</a>";