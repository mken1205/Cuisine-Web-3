/* ═══════════════════════════════════════════════
   GHOST RECIPES — LANDING PAGE (js/main.js)
   ═══════════════════════════════════════════════ */

document.addEventListener("DOMContentLoaded", () => {
  renderHero();
  renderRecentGrid();
  renderHList();
  setupSearch();
});

/* ── HERO: most recently added recipe ── */
function renderHero() {
  const recipe = Store.getLatest();
  if (!recipe) return;

  const hero = document.getElementById("heroSection");
  const sideRecipes = Store.getRecipes().filter(r => r.id !== recipe.id).slice(0, 3);

  hero.innerHTML = `
    <div class="hero-main" onclick="window.location='pages/recipe.html?id=${recipe.id}'">
      <div class="hero-image"><div class="hero-image-inner">${recipe.emoji}</div></div>
      <p class="hero-label">Latest Ghost &nbsp;·&nbsp; ${capitalize(recipe.category)} &nbsp;·&nbsp; ${recipe.cuisine}</p>
      <h1 class="hero-title">${recipe.title}</h1>
      <p class="hero-desc">${recipe.intro}</p>
      <div class="hero-meta">
        <span><span class="stars">${renderStars(recipe.rating)}</span><span class="stars-count">${recipe.rating} (${recipe.ratingCount.toLocaleString()})</span></span>
        <span>${recipe.totalTime}</span>
        <span>Serves ${recipe.serves}</span>
        <span>${recipe.difficulty}</span>
      </div>
    </div>
    <div class="hero-sidebar">
      ${sideRecipes.map(r => `
        <div class="hero-side-card" onclick="window.location='pages/recipe.html?id=${r.id}'">
          <div class="side-thumb">${r.emoji}</div>
          <div>
            <p class="side-label">${capitalize(r.category)} · ${r.cuisine}</p>
            <p class="side-title">${r.title}</p>
            <p class="side-meta"><span class="stars">${renderStars(r.rating)}</span><span class="stars-count">${r.rating}</span> &nbsp;·&nbsp; ${r.totalTime}</p>
          </div>
        </div>
      `).join("")}
    </div>
  `;
}

/* ── RECENT GRID ── */
function renderRecentGrid() {
  const recipes = Store.getRecipes().slice(0, 4);
  const grid = document.getElementById("recentGrid");

  grid.innerHTML = recipes.map(r => recipeCard(r)).join("") ||
    `<div class="empty-state">No recipes yet. Add some in the admin panel.</div>`;
}

/* ── H-LIST (street food quick rails) ── */
function renderHList() {
  const recipes = Store.getRecipes().filter(r => r.tags.includes("street") || r.tags.includes("quick"));
  const list = document.getElementById("hList");

  list.innerHTML = recipes.map(r => `
    <div class="h-card" onclick="window.location='pages/recipe.html?id=${r.id}'">
      <div class="h-card-thumb"><div class="h-card-thumb-inner">${r.emoji}</div></div>
      <p class="h-card-label">${r.cuisine}</p>
      <p class="h-card-title">${r.title}</p>
      <p class="h-card-time"><span class="stars" style="font-size:.5rem">${renderStars(r.rating)}</span> ${r.rating} · ${r.totalTime}</p>
    </div>
  `).join("") || `<p style="padding:2rem;font-family:var(--mono);font-size:.7rem;color:var(--text3)">No street recipes yet.</p>`;
}

/* ── SEARCH ── */
function setupSearch() {
  document.getElementById("searchInput").addEventListener("input", e => {
    const val = e.target.value.toLowerCase().trim();
    if (val.length < 2) return;
    const match = Store.getRecipes().find(r =>
      r.title.toLowerCase().includes(val) ||
      r.cuisine.toLowerCase().includes(val) ||
      r.category.toLowerCase().includes(val)
    );
    if (match) window.location = `pages/recipe.html?id=${match.id}`;
  });
}

/* ── HELPERS ── */
function recipeCard(r) {
  return `
    <div class="recipe-card fade-in" onclick="window.location='pages/recipe.html?id=${r.id}'">
      <div class="card-thumb"><div class="card-thumb-inner">${r.emoji}</div></div>
      <p class="card-label">${capitalize(r.category)} · ${r.cuisine}</p>
      <h3 class="card-title">${r.title}</h3>
      <p class="card-desc">${r.description}</p>
      <div class="card-footer">
        <span class="card-time">⏱ ${r.totalTime} &nbsp;·&nbsp; <span class="stars" style="font-size:.5rem">${renderStars(r.rating)}</span> ${r.rating}</span>
        <button class="save-btn" onclick="event.stopPropagation();toggleSave(this,'${r.id}')">♡ Save</button>
      </div>
    </div>`;
}

function renderStars(rating) {
  const full = Math.floor(rating);
  const half = rating % 1 >= 0.5;
  return "★".repeat(full) + (half ? "☆" : "") + "☆".repeat(5 - full - (half ? 1 : 0));
}

function capitalize(s) { return s ? s.charAt(0).toUpperCase() + s.slice(1) : s; }

function toggleSave(btn, id) {
  const saved = btn.classList.toggle("saved");
  btn.textContent = saved ? "♥ Saved" : "♡ Save";
}
