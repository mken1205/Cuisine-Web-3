document.addEventListener("DOMContentLoaded", () => {
  const blocks = [
    document.querySelector(".rp-hero"),
    document.querySelector(".rp-ingredients"),
    document.querySelector(".rp-steps")
  ];

  blocks.forEach((block, index) => {
    if (!block) return;

    block.style.opacity = "0";
    block.style.transform = "translateY(14px)";
    block.style.transition = "opacity 0.35s ease, transform 0.35s ease";

    setTimeout(() => {
      block.style.opacity = "1";
      block.style.transform = "translateY(0)";
    }, 120 + index * 120);
  });
});