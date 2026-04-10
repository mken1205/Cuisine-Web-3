document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector('form[action="login.php"]');
  if (!form) return;

  const loginInput = document.getElementById("login");
  const passwordInput = document.getElementById("password");

  function removeError(input) {
    input.classList.remove("input-error");
    const next = input.parentElement.querySelector(".field-error");
    if (next) next.remove();
  }

  function showError(input, message) {
    removeError(input);
    input.classList.add("input-error");

    const error = document.createElement("div");
    error.className = "field-error";
    error.textContent = message;
    input.parentElement.appendChild(error);
  }

  [loginInput, passwordInput].forEach((input) => {
    input.addEventListener("input", () => removeError(input));
  });

  form.addEventListener("submit", (e) => {
    let valid = true;

    if (loginInput.value.trim() === "") {
      showError(loginInput, "Veuillez entrer votre login.");
      valid = false;
    }

    if (passwordInput.value.trim() === "") {
      showError(passwordInput, "Veuillez entrer votre mot de passe.");
      valid = false;
    } else if (passwordInput.value.trim().length < 4) {
      showError(passwordInput, "Mot de passe trop court.");
      valid = false;
    }

    if (!valid) {
      e.preventDefault();
      form.classList.add("shake");
      setTimeout(() => form.classList.remove("shake"), 300);
    }
  });
});