/* ═══════════════════════════════════════════════
   GHOST RECIPES — SINGLE RECIPE PAGE (js/recipe.js)
   Reads ?id= from URL and renders the full recipe.
   ═══════════════════════════════════════════════ */

document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);
  const id = params.get("id");

  if (!id) { showError("No recipe specified."); return; }

  const recipe = Store.getRecipeById(id);
  if (!recipe) { showError(`Recipe "${id}" not found.`); return; }

  document.title = `${recipe.title} — Ghost Recipes`;
  renderRecipe(recipe);
});

function renderRecipe(r) {
  const root = document.getElementById("recipeRoot");

  root.innerHTML = `
    <!-- Back link -->
    <a class="rp-back" href="recipes.html">← Back to collection</a>

    <!-- Hero -->
    <div class="rp-hero">
      <div class="rp-hero-img">${r.emoji}</div>
      <div class="rp-hero-meta">
        <p class="rp-label">${r.id.toString().padStart(3,"0").toUpperCase()} &nbsp;·&nbsp; ${capitalize(r.category)} &nbsp;·&nbsp; ${r.cuisine} &nbsp;·&nbsp; ${r.region}</p>
        <h1 class="rp-title">${r.title}</h1>
        <p class="rp-byline">
          By the Ghost Kitchen Archive &nbsp;·&nbsp;
          <span class="stars">${renderStars(r.rating)}</span>
          <span class="stars-count">${r.rating} (${r.ratingCount.toLocaleString()} made this)</span>
        </p>
        <p class="rp-intro">${r.intro}</p>
      </div>
    </div>

    <!-- Stats strip -->
    <div class="rp-stats">
      <div class="rp-stat"><p class="rp-stat-label">Total Time</p><p class="rp-stat-val">${r.totalTime}</p></div>
      <div class="rp-stat"><p class="rp-stat-label">Active Time</p><p class="rp-stat-val">${r.activeTime}</p></div>
      <div class="rp-stat"><p class="rp-stat-label">Serves</p><p class="rp-stat-val">${r.serves}</p></div>
      <div class="rp-stat"><p class="rp-stat-label">Difficulty</p><p class="rp-stat-val">${r.difficulty}</p></div>
    </div>

    <!-- Body: ingredients + steps -->
    <div class="rp-body">
      <aside class="rp-ingredients">
        <p class="rp-col-label">Ingredients</p>
        ${renderIngredientGroups(r.ingredientGroups)}
        ${r.yield ? `<p class="ing-yield">${r.yield}</p>` : ""}
      </aside>
      <div class="rp-steps">
        <!-- Story / blog intro -->
        <p class="rp-blog-intro">${r.storyParagraphs.join("<br><br>")}</p>

        <!-- Sections (headings, quotes, paragraphs) -->
        ${renderSections(r.sections)}

        <!-- Numbered steps -->
        <span class="steps-label">Method</span>
        ${r.steps.map((s, i) => `
          <div class="step">
            <span class="step-n">${String(i + 1).padStart(2, "0")}</span>
            <p class="step-t">${s}</p>
          </div>
        `).join("")}
      </div>
    </div>

    <!-- Related recipes -->
    ${renderRelated(r)}
  `;
}

function renderIngredientGroups(groups) {
  return groups.map(g => `
    ${g.name ? `<p class="ing-group-title">${g.name}</p>` : ""}
    ${g.items.map(item => `
      <div class="ing-item">
        <span>${item.name}</span>
        <span class="ing-qty">${item.qty}</span>
      </div>
    `).join("")}
  `).join("");
}

function renderSections(sections) {
  if (!sections || !sections.length) return "";
  return sections.map(s => {
    if (s.quote) return `<blockquote>${s.quote}</blockquote>`;
    if (s.heading) return `
      <h2 class="rp-blog-h">${s.heading}</h2>
      ${(s.paragraphs || []).map(p => `<p class="rp-blog-p">${p}</p>`).join("")}
    `;
    return "";
  }).join("");
}

function renderRelated(current) {
  const all = Store.getRecipes();
  const related = all
    .filter(r => r.id !== current.id && (r.category === current.category || r.cuisine === current.cuisine))
    .slice(0, 4);

  if (!related.length) return "";

  return `
    <div style="max-width:1280px;margin:0 auto;border-top:1px solid var(--border)">
      <div class="section-head" style="margin:0">
        <h2 class="section-title">More from the archive</h2>
        <a class="section-link" href="recipes.html">See all →</a>
      </div>
      <div class="card-grid" style="border-bottom:none">
        ${related.map(r => `
          <div class="recipe-card" onclick="window.location='recipe.html?id=${r.id}'">
            <div class="card-thumb"><div class="card-thumb-inner">${r.emoji}</div></div>
            <p class="card-label">${capitalize(r.category)} · ${r.cuisine}</p>
            <h3 class="card-title">${r.title}</h3>
            <p class="card-desc">${r.description}</p>
            <div class="card-footer">
              <span class="card-time">⏱ ${r.totalTime}</span>
              <span class="stars" style="font-size:.5rem">${renderStars(r.rating)}</span>
            </div>
          </div>
        `).join("")}
      </div>
    </div>
  `;
}

function showError(msg) {
  document.getElementById("recipeRoot").innerHTML = `
    <div style="padding:6rem 2rem;text-align:center;font-family:var(--mono);font-size:.75rem;letter-spacing:.15em;color:var(--text3)">
      <p style="font-size:3rem;margin-bottom:1.5rem">👻</p>
      <p>${msg}</p>
      <a href="recipes.html" style="color:var(--gold);border-bottom:1px solid var(--gold2);padding-bottom:2px;margin-top:1.5rem;display:inline-block">← Back to collection</a>
    </div>`;
}

function renderStars(rating) {
  const full = Math.floor(rating);
  const half = rating % 1 >= 0.5;
  return "★".repeat(full) + (half ? "☆" : "") + "☆".repeat(5 - full - (half ? 1 : 0));
}

function capitalize(s) { return s ? s.charAt(0).toUpperCase() + s.slice(1) : s; }
