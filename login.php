<?php
session_start();
require_once "config/db.php";

// Si déjà connecté, rediriger vers le dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: admin/dashboard.php");
    exit();
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login    = trim($_POST['login']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($login === '' || $password === '') {
        $erreur = "Veuillez remplir tous les champs.";
    } else {
        // Chercher l'utilisateur en base de données
        $stmt = $pdo->prepare("SELECT * FROM user WHERE login = :login LIMIT 1");
        $stmt->execute([':login' => $login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier le mot de passe hashé
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['login']   = $user['login'];
            $_SESSION['role']    = $user['role'];
            header("Location: admin/dashboard.php");
            exit();
        } else {
            $erreur = "Login ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Ghost Recipes</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="login-wrap">
    <div class="login-box">

        <div class="login-logo">Ghost Recipes</div>
        <p class="login-sub">Espace administrateur</p>

        <?php if ($erreur !== ''): ?>
            <div class="login-error"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="login-form-group">
                <label class="login-label" for="login">Login</label>
                <input class="login-input" type="text" id="login" name="login"
                       value="<?= htmlspecialchars($_POST['login'] ?? '') ?>" required autofocus>
            </div>
            <div class="login-form-group">
                <label class="login-label" for="password">Mot de passe</label>
                <input class="login-input" type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-btn">Se connecter</button>
        </form>

        <p style="text-align:center; margin-top:1.5rem;">
            <a href="index.php" style="font-size:0.7rem; color:var(--text3); letter-spacing:0.1em;">← Retour au site</a>
        </p>

    </div>
</div>

<script src="public/js/login.js"></script>
</body>
</html>
