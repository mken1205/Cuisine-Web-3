document.addEventListener("DOMContentLoaded", () => {
  const cards = document.querySelectorAll(".recipe-card");

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("is-visible");
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12 });

  cards.forEach((card) => observer.observe(card));

  const heroForm = document.querySelector(".hero-search");
  const heroInput = heroForm ? heroForm.querySelector('input[name="q"]') : null;

  if (heroForm && heroInput) {
    heroForm.addEventListener("submit", (e) => {
      if (heroInput.value.trim() === "") {
        e.preventDefault();
        heroInput.classList.add("input-error", "shake");
        heroInput.focus();

        setTimeout(() => {
          heroInput.classList.remove("shake");
        }, 300);
      }
    });

    heroInput.addEventListener("input", () => {
      heroInput.classList.remove("input-error");
    });
  }
});