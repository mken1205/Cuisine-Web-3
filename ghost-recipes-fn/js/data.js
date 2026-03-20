/* ═══════════════════════════════════════════════
   GHOST RECIPES — DATA STORE (js/data.js)
   Single source of truth for all recipes.
   Admin panel reads/writes here via localStorage.
   ═══════════════════════════════════════════════ */

const DEFAULT_RECIPES = [
  {
    id: "birria",
    title: "Birria de Res at 2am",
    emoji: "🍲",
    category: "comfort",
    cuisine: "Mexican",
    region: "Jalisco",
    tags: ["slow", "soups", "comfort"],
    totalTime: "4–6 hours",
    activeTime: "45 min",
    serves: "6",
    difficulty: "Intermediate",
    rating: 4.9,
    ratingCount: 2341,
    highlight: true,
    intro: "There is a particular kind of hunger that only arrives after midnight. This recipe exists for that hunger — the one that asks for something warm, slow, and deeply built. Birria is not a recipe. It is a decision to take something seriously.",
    description: "The broth is a ghost itself — weeks in the making, pulled from bones left simmering through the small hours.",
    storyParagraphs: [
      "The first time I had birria was from a man who wouldn't tell me his name. He had a cart. He was there every Saturday, positioned between a laundromat and a tire shop, and his consommé was the color of old mahogany.",
      "<strong>Then one Saturday, he wasn't there.</strong> I spent three years trying to reconstruct what he made. This version is not his. It never will be. That's the nature of ghost food — it leaves impressions, not instructions."
    ],
    sections: [
      {
        heading: "The broth is everything",
        paragraphs: [
          "Birria's soul lives in its consommé. The dried chiles — guajillo, ancho, pasilla — each contribute layers: one gives fruit, one gives smoke, one gives that deep purple color that stains everything it touches.",
          "Toast them dry in a heavy pan. Don't rush this. You want them to wake up, not burn. A chile that smells like coffee and dark chocolate is a chile ready to work."
        ]
      },
      {
        quote: "The consommé should taste like something has been happening in that pot for a very long time. Because it has."
      },
      {
        heading: "On the meat",
        paragraphs: [
          "Chuck roast, oxtail, short rib — use what speaks to you. The rule is collagen. When the broth cools, it should tremble. That's how you know it worked.",
          "Marinate overnight in the chile paste. The next morning, your kitchen will smell like something important is about to happen."
        ]
      }
    ],
    ingredientGroups: [
      {
        name: "The meat",
        items: [
          { name: "Chuck roast, bone-in", qty: "1.5 kg" },
          { name: "Short rib or oxtail", qty: "500 g" }
        ]
      },
      {
        name: "The chile paste",
        items: [
          { name: "Guajillo chiles, dried", qty: "6" },
          { name: "Ancho chiles, dried", qty: "3" },
          { name: "Pasilla chiles, dried", qty: "2" },
          { name: "Roma tomatoes", qty: "4" },
          { name: "White onion", qty: "1 large" },
          { name: "Garlic cloves", qty: "8" },
          { name: "Mexican cinnamon", qty: "1 stick" },
          { name: "Black peppercorns", qty: "1 tsp" },
          { name: "Cumin seeds", qty: "1 tsp" },
          { name: "Dried oregano", qty: "1 tbsp" },
          { name: "Apple cider vinegar", qty: "2 tbsp" }
        ]
      },
      {
        name: "The braise",
        items: [
          { name: "Beef stock", qty: "2 L" },
          { name: "Bay leaves", qty: "3" }
        ]
      },
      {
        name: "To serve",
        items: [
          { name: "Corn tortillas", qty: "12" },
          { name: "White onion, diced", qty: "½" },
          { name: "Cilantro, chopped", qty: "large handful" },
          { name: "Lime wedges", qty: "3" },
          { name: "Oaxacan cheese", qty: "200 g" }
        ]
      }
    ],
    yield: "6 large servings. Broth keeps 5 days refrigerated.",
    steps: [
      "Toast chiles in a dry pan, pressing flat, until darkened and fragrant — about 30 seconds per side. Remove stems and seeds. Soak in 2 cups hot water for 20 minutes.",
      "Char the tomatoes, onion, and garlic directly over a gas flame or under a broiler until blackened in spots.",
      "Blend soaked chiles with soaking liquid, charred vegetables, and all spices with the vinegar until very smooth. Strain through a sieve.",
      "Coat the beef completely in the chile paste. Cover and refrigerate overnight, minimum 4 hours.",
      "Sear the marinated beef in a heavy pot over high heat until every surface is deeply browned. Work in batches.",
      "Add stock, bay leaves, and remaining paste. Bring to a boil, reduce to the lowest simmer, cover, and braise 3–4 hours until meat falls from bone.",
      "Remove meat and shred. Skim the fat from the broth — reserve it for dipping the tortillas.",
      "For quesabirria: dip tortilla in reserved fat, press onto a hot griddle, add meat and cheese, fold, cook until crisp and red. Dip every bite back into the hot consommé."
    ],
    createdAt: "2025-01-15"
  },
  {
    id: "elote",
    title: "Elote en Vaso",
    emoji: "🌽",
    category: "street",
    cuisine: "Mexican",
    region: "Mexico City",
    tags: ["street", "quick"],
    totalTime: "15 minutes",
    activeTime: "15 min",
    serves: "1",
    difficulty: "Easy",
    rating: 4.7,
    ratingCount: 1822,
    highlight: false,
    intro: "You don't sit down for elote en vaso. You take it from the vendor's hand, find a wall to lean against, and eat while watching the street happen around you.",
    description: "The kind you eat standing up. No table required, no forks allowed.",
    storyParagraphs: [
      "The genius of elote en vaso is that it takes something primitive — boiled corn — and layers it with opposing textures and temperatures until it becomes something unreasonable.",
      "<strong>The corn should be hot. The crema should be cold.</strong> The chile should burn slightly. The lime should cut through all of it."
    ],
    sections: [
      {
        quote: "Street food is not about technique. It is about the right things in the right order, unapologetically."
      },
      {
        heading: "The topping formula",
        paragraphs: [
          "Mayonnaise first — it coats the hot corn and begins to warm. Then crema mexicana. Then cotija, crumbled not grated. Then Tajín — more than you think. Then lime. Then eat immediately, standing if possible."
        ]
      }
    ],
    ingredientGroups: [
      {
        name: "",
        items: [
          { name: "Corn kernels (2 cobs or frozen)", qty: "250 g" },
          { name: "Butter", qty: "1 tbsp" },
          { name: "Mayonnaise", qty: "2 tbsp" },
          { name: "Crema mexicana", qty: "2 tbsp" },
          { name: "Cotija cheese, crumbled", qty: "30 g" },
          { name: "Tajín or chile-lime powder", qty: "to taste" },
          { name: "Lime", qty: "1" },
          { name: "Salt", qty: "pinch" }
        ]
      }
    ],
    yield: "Serves 1. Scale freely.",
    steps: [
      "Cook corn in boiling salted water 5 minutes, or sauté in butter over high heat until edges caramelize.",
      "Spoon the hot corn into a cup or deep bowl.",
      "Add the mayonnaise and crema. Stir gently until the corn is coated.",
      "Top with crumbled cotija — not grated. Crumbled gives pockets of salt.",
      "Dust generously with Tajín. Generously means more than you're comfortable with.",
      "Squeeze the lime over everything. Eat immediately, standing if possible."
    ],
    createdAt: "2025-01-18"
  },
  {
    id: "congee",
    title: "Congee for the Sick and the Sad",
    emoji: "🍚",
    category: "comfort",
    cuisine: "Cantonese",
    region: "Guangdong",
    tags: ["comfort", "slow", "soups"],
    totalTime: "2 hours",
    activeTime: "20 min",
    serves: "2–3",
    difficulty: "Easy",
    rating: 4.8,
    ratingCount: 3107,
    highlight: false,
    intro: "In Cantonese households, congee is the answer to most questions. Flu? Congee. Breakup? Congee. Can't sleep and the world feels wrong at 3am? There is a pot on the stove.",
    description: "A bowl that heals before you taste it. Made slow, eaten slower.",
    storyParagraphs: [
      "Congee is rice that has given up its form entirely — surrendered to water and heat until it becomes something ancient and gentle and soft.",
      "<strong>The ratio is 1:10.</strong> One cup of rice to ten cups of stock. You will doubt this. By the end, the rice will have dissolved into the liquid, becoming something that is neither rice nor soup but its own category."
    ],
    sections: [
      {
        quote: "My grandmother never measured. She just knew when it was ready — when the spoon drew a slow wave across the surface that closed behind itself."
      },
      {
        heading: "The stock matters",
        paragraphs: [
          "A good chicken or pork stock is the foundation. If you have ginger — and you should always have ginger — add coins of it to the stock from the start. The ginger is the medicine part. The warmth is the comfort part. Both are required."
        ]
      }
    ],
    ingredientGroups: [
      {
        name: "",
        items: [
          { name: "Jasmine rice", qty: "200 g" },
          { name: "Chicken or pork stock", qty: "2 L" },
          { name: "Fresh ginger, sliced", qty: "4–5 coins" },
          { name: "Garlic cloves, crushed", qty: "2" },
          { name: "Sesame oil", qty: "1 tsp" },
          { name: "Soy sauce", qty: "to taste" },
          { name: "White pepper", qty: "pinch" }
        ]
      },
      {
        name: "To garnish (choose one)",
        items: [
          { name: "Spring onion, sliced", qty: "2 stalks" },
          { name: "Century egg, sliced", qty: "1" },
          { name: "Fried shallots", qty: "handful" }
        ]
      }
    ],
    yield: "Serves 2–3. Add water when reheating.",
    steps: [
      "Rinse the rice once under cold water. Don't over-rinse — you want some starch. That starch makes congee silky rather than watery.",
      "Bring the stock to a boil with the ginger and crushed garlic. Add the rinsed rice and stir once.",
      "Reduce to the very lowest simmer your stove can manage. Cover with the lid slightly ajar. Cook 60–90 minutes, stirring every 15 minutes.",
      "The congee is ready when the rice has dissolved completely and the texture is uniform and thick.",
      "Season with soy sauce and white pepper. Finish with a drizzle of sesame oil.",
      "Ladle into deep bowls. Top with chosen garnishes. Eat slowly, preferably in silence."
    ],
    createdAt: "2025-01-22"
  },
  {
    id: "banh-mi",
    title: "Bánh Mì at the Cart on Fifth",
    emoji: "🥖",
    category: "street",
    cuisine: "Vietnamese",
    region: "Saigon",
    tags: ["street", "quick"],
    totalTime: "30 min + overnight",
    activeTime: "30 min",
    serves: "2",
    difficulty: "Easy",
    rating: 4.6,
    ratingCount: 987,
    highlight: false,
    intro: "Some recipes are recipes. Some are eulogies. This is both. The cart on Fifth Street disappeared one Tuesday in November. What remained: a baguette with impossible crunch, and a woman who never wrote anything down.",
    description: "The cart is gone now. The recipe remains. A ghost in the truest sense.",
    storyParagraphs: [
      "The genius of bánh mì is that it is a French baguette that has been entirely taken over by Vietnam and refuses to give it back.",
      "<strong>She made these for thirty years.</strong> I watched her for one. This is what I remember."
    ],
    sections: [
      {
        quote: "The bread must shatter when you bite it. If it doesn't shatter, it's not ready."
      },
      {
        heading: "The pickle is non-negotiable",
        paragraphs: [
          "Equal parts daikon and carrot, julienned thin. Rice vinegar, sugar, salt, warm water to cover. Refrigerate overnight. They should taste sharp enough to make you flinch. That sharpness cuts through the richness of the pâté."
        ]
      }
    ],
    ingredientGroups: [
      {
        name: "The pickle (night before)",
        items: [
          { name: "Daikon, julienned", qty: "100 g" },
          { name: "Carrot, julienned", qty: "100 g" },
          { name: "Rice vinegar", qty: "100 ml" },
          { name: "White sugar", qty: "2 tbsp" },
          { name: "Fine salt", qty: "1 tsp" }
        ]
      },
      {
        name: "The sandwich",
        items: [
          { name: "French demi-baguette", qty: "1 per person" },
          { name: "Pork liver pâté", qty: "60 g" },
          { name: "Char siu pork, sliced", qty: "100 g" },
          { name: "Cucumber, thin sliced", qty: "½" },
          { name: "Jalapeño rings", qty: "4–6" },
          { name: "Fresh cilantro", qty: "large handful" },
          { name: "Mayonnaise", qty: "1 tbsp" },
          { name: "Maggi seasoning", qty: "a drizzle" }
        ]
      }
    ],
    yield: "Serves 2. Pickle keeps 2 weeks refrigerated.",
    steps: [
      "Night before: dissolve sugar and salt in warm rice vinegar and enough water to cover. Submerge julienned daikon and carrot. Refrigerate overnight.",
      "Toast the baguette in a hot oven at 200°C for 5 minutes. It should crack audibly when pressed.",
      "Slice open but not all the way through. Spread the inside with mayonnaise, then a heavy layer of pâté.",
      "Layer in char siu, drained pickled vegetables, then cucumber, then jalapeño.",
      "Pack in a serious amount of cilantro. This is not garnish — it is structural.",
      "Drizzle with Maggi. Close. Eat immediately, over a sink. There is no dignified way to do this."
    ],
    createdAt: "2025-02-03"
  },
  {
    id: "mac",
    title: "Baked Mac & The Family Secret",
    emoji: "🧀",
    category: "comfort",
    cuisine: "Southern US",
    region: "The South",
    tags: ["comfort"],
    totalTime: "1 hour",
    activeTime: "20 min",
    serves: "8–10",
    difficulty: "Easy",
    rating: 4.9,
    ratingCount: 4502,
    highlight: false,
    intro: "This recipe does not appear in any cookbook. It existed only in the memory of one woman, passed in pieces to her children, never written down because she believed you had to earn a recipe by watching it made enough times.",
    description: "Every family has one. This is ours. Written down for the first time.",
    storyParagraphs: [
      "There are two kinds of macaroni and cheese. The kind from a packet, and the kind baked in a pan with a crust that crackles when you press it.",
      "<strong>This is the second kind.</strong> The secret is the egg. Two eggs whisked into the custard base give the whole thing structure. The mac should quiver when you cut into it."
    ],
    sections: [
      {
        quote: "She always said three cheeses minimum. She always used five."
      },
      {
        heading: "On the cheese",
        paragraphs: [
          "Sharp cheddar is the spine. Gruyère is the sophistication it didn't know it needed. American cheese is the secret — don't argue. It melts clean and brings everything together in a way that pure aged cheeses refuse to do."
        ]
      }
    ],
    ingredientGroups: [
      {
        name: "",
        items: [
          { name: "Elbow macaroni", qty: "500 g" },
          { name: "Sharp cheddar, grated", qty: "300 g" },
          { name: "Gruyère, grated", qty: "150 g" },
          { name: "American cheese", qty: "2 slices" },
          { name: "Whole milk", qty: "500 ml" },
          { name: "Evaporated milk", qty: "1 can" },
          { name: "Eggs", qty: "2 large" },
          { name: "Unsalted butter", qty: "60 g" },
          { name: "Dry mustard powder", qty: "1 tsp" },
          { name: "Cayenne pepper", qty: "¼ tsp" },
          { name: "Salt and white pepper", qty: "to taste" }
        ]
      }
    ],
    yield: "Serves 8–10. A dish for crowds, holidays, and grief.",
    steps: [
      "Cook macaroni in heavily salted water until just barely al dente. Drain and toss immediately with the butter.",
      "Whisk together eggs, whole milk, and evaporated milk with mustard powder, cayenne, salt, and white pepper.",
      "Combine buttered macaroni with two-thirds of the grated cheeses and the torn American cheese. Stir until cheese begins to melt.",
      "Pour the egg-milk mixture over the pasta and stir gently. Pour into a buttered 9×13 baking dish.",
      "Top with remaining grated cheese. Bake at 175°C (350°F) for 35–40 minutes until top is golden.",
      "Rest 10 minutes before serving. The custard needs to set. Resist."
    ],
    createdAt: "2025-02-10"
  },
  {
    id: "dosa",
    title: "Masala Dosa, No Shortcuts",
    emoji: "🫓",
    category: "street",
    cuisine: "South Indian",
    region: "Karnataka",
    tags: ["street", "fermented", "vegetarian"],
    totalTime: "24 hrs + 30 min",
    activeTime: "30 min",
    serves: "4",
    difficulty: "Advanced",
    rating: 4.8,
    ratingCount: 1430,
    highlight: false,
    intro: "You cannot make a real dosa fast. The batter must ferment. The fermentation takes the night. The night is not optional. Any recipe that promises dosa in under an hour is lying to you about what dosa is.",
    description: "Fermented batter. Paper-thin. The patience modern life forgot.",
    storyParagraphs: [
      "Dosa is what happens when you trust time and live cultures to do the work that machines cannot. The fermentation is where the flavor lives.",
      "<strong>Grind with cold water only</strong> — the cold matters; heat kills the culture before it starts. In 18–24 hours the batter will have grown, bubbled, and become something that smells faintly sour and deeply alive."
    ],
    sections: [
      {
        quote: "The batter is ready when it smells like the street outside a tiffin shop at 7am."
      }
    ],
    ingredientGroups: [
      {
        name: "The batter (start night before)",
        items: [
          { name: "Parboiled rice", qty: "300 g" },
          { name: "Urad dal (split)", qty: "100 g" },
          { name: "Fenugreek seeds", qty: "½ tsp" },
          { name: "Salt", qty: "1½ tsp" }
        ]
      },
      {
        name: "The masala filling",
        items: [
          { name: "Potatoes, boiled", qty: "4 medium" },
          { name: "Mustard seeds", qty: "1 tsp" },
          { name: "Curry leaves", qty: "12" },
          { name: "Green chillies", qty: "2" },
          { name: "Ginger, grated", qty: "1 tsp" },
          { name: "Turmeric", qty: "½ tsp" },
          { name: "White onion, thin sliced", qty: "1" },
          { name: "Neutral oil or ghee", qty: "2 tbsp" }
        ]
      }
    ],
    yield: "Makes 8–10 dosas. Batter improves on day 2.",
    steps: [
      "Soak rice and urad dal separately in cold water for 6–8 hours. Soak the fenugreek seeds with the dal.",
      "Drain and grind both separately with cold water until very smooth. Combine, add salt, mix well. Cover loosely and leave in a warm spot (28–32°C) for 18–24 hours.",
      "Heat oil in a pan. Add mustard seeds and cover — they will pop. Add curry leaves, chillies, and ginger. Sauté 30 seconds.",
      "Add sliced onion and cook until soft and golden. Add turmeric. Add boiled potatoes, roughly crushed. Season. Cook 2 minutes.",
      "Heat a flat cast-iron or non-stick pan over high heat until very hot. Reduce to medium-high. Pour a ladleful of batter and spread in fast outward circles until paper-thin.",
      "Drizzle oil around the edges. Cook until edges lift and bottom is golden and crisp — about 2 minutes. Spoon masala along the center, fold, serve immediately."
    ],
    createdAt: "2025-02-18"
  }
];

/* ── Storage helpers ── */
const Store = {
  RECIPES_KEY: "ghost_recipes",
  AUTH_KEY: "ghost_auth",

  getRecipes() {
    try {
      const raw = localStorage.getItem(this.RECIPES_KEY);
      return raw ? JSON.parse(raw) : [...DEFAULT_RECIPES];
    } catch { return [...DEFAULT_RECIPES]; }
  },

  saveRecipes(recipes) {
    localStorage.setItem(this.RECIPES_KEY, JSON.stringify(recipes));
  },

  getRecipeById(id) {
    return this.getRecipes().find(r => r.id === id) || null;
  },

  addRecipe(recipe) {
    const recipes = this.getRecipes();
    recipes.unshift(recipe);
    this.saveRecipes(recipes);
  },

  updateRecipe(id, updated) {
    const recipes = this.getRecipes();
    const idx = recipes.findIndex(r => r.id === id);
    if (idx !== -1) { recipes[idx] = { ...recipes[idx], ...updated }; this.saveRecipes(recipes); return true; }
    return false;
  },

  deleteRecipe(id) {
    const recipes = this.getRecipes().filter(r => r.id !== id);
    this.saveRecipes(recipes);
  },

  getLatest() {
    const recipes = this.getRecipes();
    return recipes.reduce((latest, r) => {
      return !latest || r.createdAt > latest.createdAt ? r : latest;
    }, null);
  },

  getHighlighted() {
    const recipes = this.getRecipes();
    return recipes.find(r => r.highlight) || recipes[0] || null;
  },

  isLoggedIn() {
    return sessionStorage.getItem(this.AUTH_KEY) === "true";
  },

  login(pass) {
    if (pass === "ghost2025") { sessionStorage.setItem(this.AUTH_KEY, "true"); return true; }
    return false;
  },

  logout() { sessionStorage.removeItem(this.AUTH_KEY); }
};
