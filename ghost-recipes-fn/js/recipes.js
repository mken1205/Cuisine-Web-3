/* ═══════════════════════════════════════════════
   GHOST RECIPES — COLLECTION PAGE (js/recipes.js)
   ═══════════════════════════════════════════════ */

let currentCategory = "all";
let currentSearch = "";

document.addEventListener("DOMContentLoaded", () => {
  renderAll();
  setupCategoryNav();
  setupSearch();
});

function renderAll() {
  const recipes = Store.getRecipes();
  const filtered = filter(recipes);
  renderGrid(filtered);
  updateCount(filtered.length, recipes.length);
}

function filter(recipes) {
  return recipes.filter(r => {
    const matchesCat = currentCategory === "all" || r.category === currentCategory || r.tags.includes(currentCategory);
    const matchesSearch = !currentSearch || r.title.toLowerCase().includes(currentSearch) || r.cuisine.toLowerCase().includes(currentSearch) || r.description.toLowerCase().includes(currentSearch);
    return matchesCat && matchesSearch;
  });
}

function renderGrid(recipes) {
  const grid = document.getElementById("collectionGrid");
  if (!grid) return;

  if (recipes.length === 0) {
    grid.innerHTML = `<div class="empty-state">No recipes match your search.</div>`;
    return;
  }

  grid.innerHTML = recipes.map(r => `
    <div class="recipe-card fade-in" onclick="window.location='recipe.html?id=${r.id}'">
      <div class="card-thumb"><div class="card-thumb-inner">${r.emoji}</div></div>
      <p class="card-label">${capitalize(r.category)} · ${r.cuisine}</p>
      <h3 class="card-title">${r.title}</h3>
      <p class="card-desc">${r.description}</p>
      <div class="card-footer">
        <span class="card-time">⏱ ${r.totalTime} &nbsp;·&nbsp; <span class="stars" style="font-size:.5rem">${renderStars(r.rating)}</span> ${r.rating}</span>
        <button class="save-btn" onclick="event.stopPropagation();toggleSave(this)">♡ Save</button>
      </div>
    </div>
  `).join("");
}

function updateCount(shown, total) {
  const el = document.getElementById("recipeCount");
  if (el) el.textContent = shown === total ? `${total} recipes` : `${shown} of ${total} recipes`;
}

function setupCategoryNav() {
  document.querySelectorAll(".cat-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      document.querySelectorAll(".cat-btn").forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
      currentCategory = btn.dataset.cat || "all";
      renderAll();
    });
  });
}

function setupSearch() {
  const input = document.getElementById("searchInput");
  if (!input) return;
  input.addEventListener("input", e => {
    currentSearch = e.target.value.toLowerCase().trim();
    renderAll();
  });
}

function toggleSave(btn) {
  const saved = btn.classList.toggle("saved");
  btn.textContent = saved ? "♥ Saved" : "♡ Save";
}

function renderStars(rating) {
  const full = Math.floor(rating);
  const half = rating % 1 >= 0.5;
  return "★".repeat(full) + (half ? "☆" : "") + "☆".repeat(5 - full - (half ? 1 : 0));
}

function capitalize(s) { return s ? s.charAt(0).toUpperCase() + s.slice(1) : s; }
