/* ═══════════════════════════════════════════════
   GHOST RECIPES — ADMIN PANEL (js/admin.js)
   Full CRUD: create, edit, delete recipes.
   Auth-gated via sessionStorage.
   ═══════════════════════════════════════════════ */

let editingId = null; // null = create mode, string = edit mode

document.addEventListener("DOMContentLoaded", () => {
  // Auth gate
  if (!Store.isLoggedIn()) {
    window.location = "login.html";
    return;
  }

  setupSidebar();
  renderDashboard();
  renderRecipeList();
  setupForm();
  setupLogout();
});

/* ── SIDEBAR NAVIGATION ── */
function setupSidebar() {
  document.querySelectorAll(".sidebar-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      const panel = btn.dataset.panel;
      switchPanel(panel);
    });
  });
}

function switchPanel(panelId) {
  document.querySelectorAll(".admin-panel").forEach(p => p.classList.remove("active"));
  document.querySelectorAll(".sidebar-btn").forEach(b => b.classList.remove("active"));
  const panel = document.getElementById(`panel-${panelId}`);
  const btn = document.querySelector(`[data-panel="${panelId}"]`);
  if (panel) panel.classList.add("active");
  if (btn) btn.classList.add("active");
}

/* ── DASHBOARD ── */
function renderDashboard() {
  const recipes = Store.getRecipes();
  const totalMade = recipes.reduce((sum, r) => sum + (r.ratingCount || 0), 0);
  const avgRating = recipes.length ? (recipes.reduce((sum, r) => sum + (r.rating || 0), 0) / recipes.length).toFixed(1) : "—";
  const latest = Store.getLatest();

  document.getElementById("stat-total").textContent = recipes.length;
  document.getElementById("stat-made").textContent = totalMade.toLocaleString();
  document.getElementById("stat-rating").textContent = avgRating;
  document.getElementById("stat-latest").textContent = latest ? latest.title.split(" ").slice(0, 3).join(" ") + "…" : "—";

  // Recent activity list
  const activityEl = document.getElementById("activityList");
  activityEl.innerHTML = recipes.slice(0, 5).map(r => `
    <tr>
      <td class="td-title">${r.emoji} ${r.title}</td>
      <td class="td-tag">${capitalize(r.category)}</td>
      <td>${r.cuisine}</td>
      <td>${r.totalTime}</td>
      <td><span class="stars" style="font-size:.55rem">${renderStars(r.rating)}</span> ${r.rating}</td>
      <td>${r.createdAt || "—"}</td>
    </tr>
  `).join("");
}

/* ── RECIPE LIST ── */
function renderRecipeList() {
  const recipes = Store.getRecipes();
  const tbody = document.getElementById("recipeListBody");

  tbody.innerHTML = recipes.map(r => `
    <tr>
      <td class="td-title">${r.emoji} ${r.title}</td>
      <td class="td-tag">${capitalize(r.category)}</td>
      <td>${r.cuisine}</td>
      <td>${r.difficulty}</td>
      <td>${r.totalTime}</td>
      <td>
        <div class="table-actions">
          <button class="tbl-btn" onclick="startEdit('${r.id}')">Edit</button>
          <button class="tbl-btn" onclick="viewRecipe('${r.id}')">View</button>
          <button class="tbl-btn danger" onclick="confirmDelete('${r.id}', '${r.title.replace(/'/g,"\\'")}')">Delete</button>
        </div>
      </td>
    </tr>
  `).join("") || `<tr><td colspan="6" style="padding:2rem;text-align:center;font-family:var(--mono);font-size:.7rem;color:var(--text3)">No recipes yet.</td></tr>`;
}

function viewRecipe(id) {
  window.open(`recipe.html?id=${id}`, "_blank");
}

/* ── DELETE ── */
function confirmDelete(id, title) {
  if (confirm(`Delete "${title}"? This cannot be undone.`)) {
    Store.deleteRecipe(id);
    renderRecipeList();
    renderDashboard();
    showToast("Recipe deleted.", "success");
  }
}

/* ── FORM: CREATE / EDIT ── */
function setupForm() {
  // Ingredient rows
  document.getElementById("addIngredientBtn").addEventListener("click", () => {
    addIngredientRow();
  });

  // Step rows
  document.getElementById("addStepBtn").addEventListener("click", () => {
    addStepRow();
  });

  // Form submit
  document.getElementById("recipeForm").addEventListener("submit", e => {
    e.preventDefault();
    saveRecipe();
  });

  // Cancel
  document.getElementById("cancelBtn").addEventListener("click", () => {
    resetForm();
    switchPanel("list");
  });

  // New recipe button
  document.getElementById("newRecipeBtn").addEventListener("click", () => {
    resetForm();
    switchPanel("create");
  });
}

function startEdit(id) {
  const recipe = Store.getRecipeById(id);
  if (!recipe) return;

  editingId = id;
  populateForm(recipe);
  switchPanel("create");

  document.getElementById("formPanelTitle").textContent = "Edit Recipe";
  document.getElementById("formPanelSub").textContent = `Editing: ${recipe.title}`;
  document.getElementById("submitBtn").textContent = "Save Changes";
}

function populateForm(r) {
  setValue("f-title", r.title);
  setValue("f-emoji", r.emoji);
  setValue("f-category", r.category);
  setValue("f-cuisine", r.cuisine);
  setValue("f-region", r.region || "");
  setValue("f-totalTime", r.totalTime);
  setValue("f-activeTime", r.activeTime);
  setValue("f-serves", r.serves);
  setValue("f-difficulty", r.difficulty);
  setValue("f-rating", r.rating);
  setValue("f-ratingCount", r.ratingCount);
  setValue("f-description", r.description);
  setValue("f-intro", r.intro);
  setValue("f-yield", r.yield || "");

  // Story paragraphs
  setValue("f-story", r.storyParagraphs ? r.storyParagraphs.join("\n\n") : "");

  // Ingredients — flatten all groups
  const ingList = document.getElementById("ingredientList");
  ingList.innerHTML = "";
  (r.ingredientGroups || []).forEach(group => {
    (group.items || []).forEach(item => {
      addIngredientRow(item.name, item.qty);
    });
  });
  if (!ingList.children.length) addIngredientRow();

  // Steps
  const stepList = document.getElementById("stepList");
  stepList.innerHTML = "";
  (r.steps || []).forEach(s => addStepRow(s));
  if (!stepList.children.length) addStepRow();
}

function resetForm() {
  editingId = null;
  document.getElementById("recipeForm").reset();
  document.getElementById("ingredientList").innerHTML = "";
  document.getElementById("stepList").innerHTML = "";
  addIngredientRow();
  addStepRow();
  document.getElementById("formPanelTitle").textContent = "Add New Recipe";
  document.getElementById("formPanelSub").textContent = "Fill in the details below to publish a new ghost.";
  document.getElementById("submitBtn").textContent = "Publish Recipe";
}

function saveRecipe() {
  const title = getValue("f-title").trim();
  if (!title) { showToast("Title is required.", "error"); return; }

  const id = editingId || slugify(title);
  const storyRaw = getValue("f-story").trim();

  const recipe = {
    id,
    title,
    emoji: getValue("f-emoji") || "🍽️",
    category: getValue("f-category"),
    cuisine: getValue("f-cuisine").trim(),
    region: getValue("f-region").trim(),
    tags: buildTags(getValue("f-category")),
    totalTime: getValue("f-totalTime").trim(),
    activeTime: getValue("f-activeTime").trim(),
    serves: getValue("f-serves").trim(),
    difficulty: getValue("f-difficulty"),
    rating: parseFloat(getValue("f-rating")) || 4.5,
    ratingCount: parseInt(getValue("f-ratingCount")) || 0,
    highlight: false,
    description: getValue("f-description").trim(),
    intro: getValue("f-intro").trim(),
    storyParagraphs: storyRaw.split(/\n\n+/).filter(Boolean),
    sections: [],
    ingredientGroups: collectIngredients(),
    yield: getValue("f-yield").trim(),
    steps: collectSteps(),
    createdAt: editingId ? (Store.getRecipeById(editingId)?.createdAt || today()) : today()
  };

  if (editingId) {
    Store.updateRecipe(editingId, recipe);
    showToast(`"${title}" updated successfully.`, "success");
  } else {
    Store.addRecipe(recipe);
    showToast(`"${title}" published to the archive.`, "success");
  }

  resetForm();
  renderRecipeList();
  renderDashboard();
  switchPanel("list");
}

/* ── INGREDIENT ROWS ── */
function addIngredientRow(name = "", qty = "") {
  const list = document.getElementById("ingredientList");
  const row = document.createElement("div");
  row.className = "dynamic-row ing-row";
  row.innerHTML = `
    <input class="form-input ing-name" type="text" placeholder="e.g. Guajillo chiles, dried" value="${esc(name)}">
    <input class="form-input ing-qty" type="text" placeholder="e.g. 6" value="${esc(qty)}">
    <button type="button" class="remove-row-btn" onclick="this.parentElement.remove()">×</button>
  `;
  list.appendChild(row);
}

function collectIngredients() {
  const rows = document.querySelectorAll("#ingredientList .ing-row");
  const items = [];
  rows.forEach(row => {
    const name = row.querySelector(".ing-name").value.trim();
    const qty = row.querySelector(".ing-qty").value.trim();
    if (name) items.push({ name, qty });
  });
  return [{ name: "", items }];
}

/* ── STEP ROWS ── */
function addStepRow(text = "") {
  const list = document.getElementById("stepList");
  const idx = list.children.length + 1;
  const row = document.createElement("div");
  row.className = "dynamic-row step-row";
  row.innerHTML = `
    <textarea class="form-textarea step-text" rows="2" placeholder="Step ${idx}…">${esc(text)}</textarea>
    <button type="button" class="remove-row-btn" onclick="this.parentElement.remove()">×</button>
  `;
  list.appendChild(row);
}

function collectSteps() {
  return [...document.querySelectorAll("#stepList .step-text")]
    .map(t => t.value.trim())
    .filter(Boolean);
}

/* ── LOGOUT ── */
function setupLogout() {
  document.getElementById("logoutBtn").addEventListener("click", () => {
    Store.logout();
    window.location = "login.html";
  });
}

/* ── HELPERS ── */
function getValue(id) { return (document.getElementById(id)?.value || ""); }
function setValue(id, val) { const el = document.getElementById(id); if (el) el.value = val; }
function esc(s) { return String(s).replace(/"/g, "&quot;"); }
function slugify(s) { return s.toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/^-|-$/g, "").substring(0, 50); }
function today() { return new Date().toISOString().split("T")[0]; }
function capitalize(s) { return s ? s.charAt(0).toUpperCase() + s.slice(1) : s; }
function buildTags(category) {
  const map = { comfort: ["comfort"], street: ["street", "quick"], vegetarian: ["vegetarian"] };
  return map[category] || [category];
}
function renderStars(rating) {
  const full = Math.floor(rating);
  const half = rating % 1 >= 0.5;
  return "★".repeat(full) + (half ? "☆" : "") + "☆".repeat(5 - full - (half ? 1 : 0));
}

/* ── TOAST ── */
function showToast(msg, type = "success") {
  const toast = document.getElementById("toast");
  toast.textContent = (type === "success" ? "✓  " : "✕  ") + msg;
  toast.className = `toast ${type} show`;
  setTimeout(() => toast.classList.remove("show"), 3000);
}

