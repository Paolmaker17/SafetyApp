// Gestione Tema
const toggle = document.getElementById("toggle");
const applyTheme = (theme) => {
  if (theme === "dark") {
    document.documentElement.classList.add("dark");
    toggle.checked = true;
  } else {
    document.documentElement.classList.remove("dark");
    toggle.checked = false;
  }
  localStorage.setItem("theme", theme);
};

toggle.addEventListener("change", () => {
  const theme = toggle.checked ? "dark" : "light";
  applyTheme(theme);
});

window.addEventListener("DOMContentLoaded", () => {
  const savedTheme = localStorage.getItem("theme") || "light";
  applyTheme(savedTheme);
});