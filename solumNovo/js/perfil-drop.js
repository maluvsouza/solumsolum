document.addEventListener("DOMContentLoaded", () => {

  const dropdown = document.querySelector(".perfil-dropdown");
  const btn = document.querySelector(".perfil-btn");

  btn.addEventListener("click", () => {
    dropdown.classList.toggle("show");
  });

  // Fechar clicando fora
  document.addEventListener("click", (e) => {
    if (!dropdown.contains(e.target)) {
      dropdown.classList.remove("show");
    }
  });

});
