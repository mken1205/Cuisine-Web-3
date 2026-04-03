<?php
// On veut protèger les pages admin = rediriger si non connecté
// je dois l ajouter pour toutes les pages admin afin de securiser
session_start();


// user id : pour savoir si user est connecte | role : pour savoir s'il est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>
