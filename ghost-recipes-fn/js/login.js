//Redirige l'utilisateur si il est déjà connecté

if (Store.isLoggedIn()) window.location = "admin.html";

document.getElementById("loginForm").addEventListener("submit", e => {
    e.preventDefault();
    const pass = document.getElementById("passwordInput").value;
    if (Store.login(pass)) {
        window.location = "admin.html";
    } else {
      document.getElementById("loginError").classList.add("show");
      document.getElementById("passwordInput").value = "";
      document.getElementById("passwordInput").focus();
    }
});