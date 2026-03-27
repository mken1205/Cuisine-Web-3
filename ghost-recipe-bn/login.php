<?php
session_start();

if (isset($_SESSION['admin'])) {
    header("Location: admin/dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $login = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');
    

    if ($login === 'admin' && $password === 'recette') {
        $_SESSION['admin'] = true;
        header("Location: admin/dashboard.php"); 
        exit();
    } else {
        $error = "Login ou mot de passe incorrect";
    }
}

?>

