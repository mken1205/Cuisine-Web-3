<?php
session_start();

// Si déjà connecté, redirige vers dashboard
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header("Location: admin/dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Vérification stricte
    if ($login === 'admin' && $password === 'recette') {
        $_SESSION['admin'] = true;
        header("Location: admin/dashboard.php"); 
        exit();
    } else {
        $error = "Login ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login — Ghost Recipes</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="login-wrap">
  <div class="login-box">
    <div class="login-logo">
      <span class="logo-ghost" style="font-size:2rem">Ghost</span>
      <br>
      <span class="logo-recipes" style="font-size:.5rem;letter-spacing:.25em">Recipes</span>
    </div>

    <p class="login-eyebrow">Admin Access</p>

    <!-- Affichage erreur PHP -->
    <?php if (!empty($error)) : ?>
      <div class="login-error show"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group" style="margin-bottom:1rem">
        <label class="form-label">Login</label>
        <input class="form-input" 
               type="text" 
               name="login" 
               placeholder="Enter login" 
               required>
      </div>

      <div class="form-group" style="margin-bottom:1rem">
        <label class="form-label">Password</label>
        <input class="form-input" 
               type="password" 
               name="password" 
               placeholder="Enter admin password" 
               required>
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
        Enter the Kitchen →
      </button>
    </form>

    <hr class="login-divider">

    <p style="font-family:var(--mono);font-size:.58rem;letter-spacing:.1em;color:var(--text3);text-align:center">
      <a href="../index.html" style="color:var(--text3);border-bottom:1px solid var(--border2)">
        ← Back to Ghost Recipes
      </a>
    </p>
  </div>
</div>

</body>
</html>