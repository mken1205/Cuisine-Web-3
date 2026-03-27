<?php
session_start();

// Vérification de session : si l'utilisateur n'est pas connecté  renvoyer vers login
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: ../login.php");
    exit();
}

// Déconnexion si le bouton logout est cliqué
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Ghost Recipes</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header class="masthead">
  <div class="masthead-top">
    <a class="logo-wrap" href="../index.html">
      <span class="logo-ghost">Ghost</span>
      <span class="logo-recipes">Recipes</span>
    </a>
    <div style="flex:1"></div>
    <nav class="masthead-links">
      <a href="../index.html">← View Site</a>
      <a href="recipes.html">Collection</a>
      <!-- Formulaire logout -->
      <form method="POST" style="display:inline">
        <button type="submit" name="logout" style="font-family:var(--mono);font-size:.62rem;letter-spacing:.14em;text-transform:uppercase;color:var(--red);cursor:pointer;background:none;border:none">Log Out</button>
      </form>
    </nav>
  </div>
</header>

<div class="admin-layout">

  <!--SIDEBAR -->
  <aside class="admin-sidebar">
    <p class="admin-sidebar-label">Admin Panel</p>
    <button class="sidebar-btn active" data-panel="dashboard">
      <span class="btn-icon">◈</span> Dashboard
    </button>
    <button class="sidebar-btn" data-panel="list">
      <span class="btn-icon">≡</span> All Recipes
    </button>
    <button class="sidebar-btn" data-panel="create">
      <span class="btn-icon">+</span> New Recipe
    </button>
  </aside>

  <!--MAIN-->
  <main class="admin-main">

    <!-- DASHBOARD -->
    <div class="admin-panel active" id="panel-dashboard">
      <h1 class="admin-panel-title">Dashboard</h1>
      <p class="admin-panel-sub">Overview of the Ghost Recipes archive.</p>

      <div class="stat-cards">
        <div class="stat-card">
          <p class="stat-card-label">Total Recipes</p>
          <p class="stat-card-val" id="stat-total">—</p>
          <p class="stat-card-sub">in the archive</p>
        </div>
        <div class="stat-card">
          <p class="stat-card-label">Total Made</p>
          <p class="stat-card-val" id="stat-made">—</p>
          <p class="stat-card-sub">times cooked</p>
        </div>
        <div class="stat-card">
          <p class="stat-card-label">Avg Rating</p>
          <p class="stat-card-val" id="stat-rating">—</p>
          <p class="stat-card-sub">out of 5.0</p>
        </div>
        <div class="stat-card">
          <p class="stat-card-label">Latest</p>
          <p class="stat-card-val" id="stat-latest" style="font-size:1rem">—</p>
          <p class="stat-card-sub">most recently added</p>
        </div>
      </div>

      <div class="section-head" style="padding:0 0 0.75rem;margin:0 0 0">
        <h2 class="section-title" style="font-size:1rem">Recent recipes</h2>
        <button class="section-link" onclick="switchPanel('list')" style="background:none;border:none;cursor:pointer">Manage all →</button>
      </div>
      <table class="recipe-table">
        <thead>
          <tr>
            <th>Title</th><th>Category</th><th>Cuisine</th><th>Time</th><th>Rating</th><th>Added</th>
          </tr>
        </thead>
        <tbody id="activityList"></tbody>
      </table>

      <div class="btn-row" style="margin-top:2rem">
        <button class="btn btn-primary" id="newRecipeBtn">+ New Recipe</button>
      </div>
    </div>

    <!-- RECIPE LIST -->
    <div class="admin-panel" id="panel-list">
      <h1 class="admin-panel-title">All Recipes</h1>
      <p class="admin-panel-sub">Edit, delete, or preview any recipe in the archive.</p>

      <div class="btn-row" style="margin-bottom:1.5rem;margin-top:0">
        <button class="btn btn-primary" id="newRecipeBtnList" onclick="resetForm();switchPanel('create')">+ New Recipe</button>
      </div>

      <table class="recipe-table">
        <thead>
          <tr>
            <th>Title</th><th>Category</th><th>Cuisine</th><th>Difficulty</th><th>Time</th><th>Actions</th>
          </tr>
        </thead>
        <tbody id="recipeListBody"></tbody>
      </table>
    </div>

    <!-- CREATE / EDIT -->
    <div class="admin-panel" id="panel-create">
      <h1 class="admin-panel-title" id="formPanelTitle">Add New Recipe</h1>
      <p class="admin-panel-sub" id="formPanelSub">Fill in the details below to publish a new ghost.</p>

      <form id="recipeForm">

        <!-- Basic info -->
        <div class="form-grid">
          <div class="form-group span2">
            <label class="form-label" for="f-title">Recipe Title *</label>
            <input class="form-input" type="text" id="f-title" placeholder="e.g. Birria de Res at 2am" required>
          </div>
          <div class="form-group">
            <label class="form-label" for="f-emoji">Emoji</label>
            <input class="form-input" type="text" id="f-emoji" placeholder="🍲" maxlength="4">
          </div>
          <div class="form-group">
            <label class="form-label" for="f-category">Category</label>
            <select class="form-select" id="f-category">
              <option value="comfort">Comfort</option>
              <option value="street">Street Food</option>
              <option value="vegetarian">Vegetarian</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="f-cuisine">Cuisine</label>
            <input class="form-input" type="text" id="f-cuisine" placeholder="e.g. Mexican">
          </div>
          <div class="form-group">
            <label class="form-label" for="f-region">Region / Origin</label>
            <input class="form-input" type="text" id="f-region" placeholder="e.g. Jalisco">
          </div>
        </div>

        <hr class="form-divider" style="margin:1.25rem 0">

        <!-- Timing & difficulty -->
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label" for="f-totalTime">Total Time</label>
            <input class="form-input" type="text" id="f-totalTime" placeholder="e.g. 4–6 hours">
          </div>
          <div class="form-group">
            <label class="form-label" for="f-activeTime">Active Time</label>
            <input class="form-input" type="text" id="f-activeTime" placeholder="e.g. 45 min">
          </div>
          <div class="form-group">
            <label class="form-label" for="f-serves">Serves</label>
            <input class="form-input" type="text" id="f-serves" placeholder="e.g. 6">
          </div>
          <div class="form-group">
            <label class="form-label" for="f-difficulty">Difficulty</label>
            <select class="form-select" id="f-difficulty">
              <option value="Easy">Easy</option>
              <option value="Intermediate">Intermediate</option>
              <option value="Advanced">Advanced</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="f-rating">Rating (0–5)</label>
            <input class="form-input" type="number" id="f-rating" min="0" max="5" step="0.1" value="4.5">
          </div>
          <div class="form-group">
            <label class="form-label" for="f-ratingCount">Times Made</label>
            <input class="form-input" type="number" id="f-ratingCount" min="0" value="0">
          </div>
        </div>

        <hr class="form-divider" style="margin:1.25rem 0">

        <!-- Description & intro -->
        <div class="form-grid full">
          <div class="form-group">
            <label class="form-label" for="f-description">Short Description <span style="color:var(--text3)">(shown on cards)</span></label>
            <input class="form-input" type="text" id="f-description" placeholder="One line that haunts the reader.">
          </div>
          <div class="form-group">
            <label class="form-label" for="f-intro">Intro <span style="color:var(--text3)">(shown on recipe hero)</span></label>
            <textarea class="form-textarea" id="f-intro" rows="3" placeholder="The opening paragraph — atmospheric, personal."></textarea>
          </div>
          <div class="form-group">
            <label class="form-label" for="f-story">Story / Blog Body <span style="color:var(--text3)">(separate paragraphs with a blank line)</span></label>
            <textarea class="form-textarea" id="f-story" rows="5" placeholder="The full ghost story behind this dish. Use a blank line to separate paragraphs. Wrap text in &lt;strong&gt;&lt;/strong&gt; for emphasis."></textarea>
          </div>
        </div>

        <hr class="form-divider" style="margin:1.25rem 0">

        <!-- Ingredients -->
        <div class="form-group" style="margin-bottom:0.75rem">
          <label class="form-label">Ingredients</label>
          <p style="font-family:var(--mono);font-size:.58rem;color:var(--text3);margin-bottom:.75rem;letter-spacing:.08em">Name on the left, quantity on the right.</p>
          <div class="dynamic-list" id="ingredientList"></div>
          <button type="button" class="add-row-btn" id="addIngredientBtn">+ Add Ingredient</button>
        </div>

        <div class="form-group" style="margin-top:1rem">
          <label class="form-label" for="f-yield">Yield / Storage Note</label>
          <input class="form-input" type="text" id="f-yield" placeholder="e.g. Serves 6. Keeps 5 days refrigerated.">
        </div>

        <hr class="form-divider" style="margin:1.25rem 0">

        <!-- Steps -->
        <div class="form-group">
          <label class="form-label">Method Steps</label>
          <p style="font-family:var(--mono);font-size:.58rem;color:var(--text3);margin-bottom:.75rem;letter-spacing:.08em">One step per row. They will be numbered automatically.</p>
          <div class="dynamic-list" id="stepList"></div>
          <button type="button" class="add-row-btn" id="addStepBtn">+ Add Step</button>
        </div>

        <hr class="form-divider" style="margin:1.25rem 0">

        <div class="btn-row">
          <button type="submit" class="btn btn-primary" id="submitBtn">Publish Recipe</button>
          <button type="button" class="btn btn-ghost" id="cancelBtn">Cancel</button>
        </div>

      </form>
    </div>

  </main>
</div>

<!-- Toast -->
<div class="toast" id="toast"></div>

<script src="../js/data.js"></script>
<script src="../js/admin.js"></script>
</body>
</html>