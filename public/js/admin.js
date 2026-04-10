document.addEventListener("DOMContentLoaded", () => {
  const forms = document.querySelectorAll("form");

  function removeFieldError(field) {
    field.classList.remove("input-error");

    const wrapper = field.closest(".form-group, .login-form-group") || field.parentElement;
    const existingError = wrapper.querySelector(".field-error");
    if (existingError) existingError.remove();
  }

  function showFieldError(field, message) {
    removeFieldError(field);
    field.classList.add("input-error");

    const wrapper = field.closest(".form-group, .login-form-group") || field.parentElement;
    const error = document.createElement("div");
    error.className = "field-error";
    error.textContent = message;
    wrapper.appendChild(error);
  }

  function validateField(field) {
    const value = field.value.trim();

    if (field.hasAttribute("required") && value === "") {
      showFieldError(field, "Ce champ est obligatoire.");
      return false;
    }

    if (field.hasAttribute("minlength")) {
      const min = parseInt(field.getAttribute("minlength"), 10);
      if (value !== "" && value.length < min) {
        showFieldError(field, `Minimum ${min} caractères.`);
        return false;
      }
    }

    if (field.type === "number") {
      const min = field.getAttribute("min");
      if (value !== "" && min !== null && Number(value) < Number(min)) {
        showFieldError(field, `La valeur minimale est ${min}.`);
        return false;
      }
    }

    removeFieldError(field);
    return true;
  }

  forms.forEach((form) => {
    const fields = form.querySelectorAll("input, textarea, select");

    fields.forEach((field) => {
      field.addEventListener("input", () => validateField(field));
      field.addEventListener("change", () => validateField(field));
    });

    form.addEventListener("submit", (e) => {
      let isValid = true;

      fields.forEach((field) => {
        if (
          field.type !== "hidden" &&
          field.type !== "submit" &&
          field.type !== "button" &&
          !validateField(field)
        ) {
          isValid = false;
        }
      });

      if (!isValid) {
        e.preventDefault();
        form.classList.add("shake");
        setTimeout(() => form.classList.remove("shake"), 300);
      }
    });
  });
});
document.addEventListener("DOMContentLoaded", () => {
  const ingredientCheckboxes = document.querySelectorAll('input[name="ingredients[]"]');

  ingredientCheckboxes.forEach((checkbox) => {
    const ingredientId = checkbox.value;
    const qtyInput = document.querySelector(`input[name="quantite[${ingredientId}]"]`);

    if (!qtyInput) return;

    function syncQtyState() {
      qtyInput.disabled = !checkbox.checked;
      if (!checkbox.checked) {
        qtyInput.value = "";
      }
    }

    syncQtyState();
    checkbox.addEventListener("change", syncQtyState);
  });
});