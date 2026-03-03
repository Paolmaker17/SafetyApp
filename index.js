
// let timeLeft = 10
// const interval = setInterval(() => {
//     timeLeft--
//     document.getElementById('countdown-bar').style.width = (timeLeft * 10) + '%'

//     if (timeLeft <= 0) {
//         clearInterval(interval)
//         popUpWindow.style.display="none"
//     }
// }, 1000)

// popUpWindow.addEventListener("mouseenter", () => {
//   document.getElementById('countdown-bar').style.width = 100 + '%';
//   clearInterval(interval)
// })

// popUpWindow.addEventListener("mouseleave", () => {
// })

// Getione Tabs
const buttons = [
  document.querySelector("[data-name='invioAllarme']"),
  document.querySelector("[data-name='utenti']"),
];
const pages = [invioAllarme, utenti];

for (const b of buttons) {
  b.onclick = (e) => {
    buttons.forEach((b) => (b.disabled = false));
    b.disabled = true;

    for (const p of pages) {
      p.style.display = p.id == b.dataset.name ? "flex" : "none";
    }
  };
}

// Getione Menù a Tendina Utente
const wrapper = document.getElementById("menu");
const dropdown = document.getElementById("dropdown");
let timeout;

wrapper.addEventListener("mouseenter", () => {
  clearTimeout(timeout);
  dropdown.classList.remove(
    "opacity-0",
    "translate-y-2",
    "pointer-events-none",
  );
  dropdown.classList.add("opacity-100", "translate-y-0");
});

wrapper.addEventListener("mouseleave", () => {
  timeout = setTimeout(() => {
    dropdown.classList.add("opacity-0", "translate-y-2", "pointer-events-none");
    dropdown.classList.remove("opacity-100", "translate-y-0");
  }, 50);
});

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

// Returns the server response, should be shown to the user
async function set_alarm(idOrMessage, desc) {
  const response = await fetch("set_alarm.php", {
    method: "POST",
    body: new URLSearchParams(
      typeof idOrMessage == "string"
        ? { id: 4 /* allarme generico */, message: idOrMessage, desc }
        : { id: idOrMessage, desc },
    ),
  });
  const json = await response.json();
  return json.message;
}

// Returns the emergency, state
// Should contain
// "STATO": 0 se tutto apposto, 1 se in emergenza
// "MESSAGGIO": il testo principale dell'allarme
// "DESCRIZIONE": la descrizione dell'allarme (il testo secondario)
async function fetch_alarm() {
  const response = await fetch("requestSchoolStateJs.php");
  if (!response.ok)
    throw `Errore HTTP: ${response.status} ${response.statusText}`;
  return await response.json();
}

async function updateIndicator() {
  try {
    const response = await fetch_alarm();
    if (response.STATO == 0) {
      statusDot.style.background = "#8EDF2A";
      statusMessage.textContent = "SafetyApp è in funzione";
      statusDesc.textContent = "Nessuna emergenza in corso";
    } else {
      statusDot.style.background = "#ffd760ff";
      statusMessage.textContent = response.MESSAGE;
      statusDesc.textContent = response.DESCRIZIONE;
    }
  } catch (e) {
    statusDot.style.background = "#D33643";
    statusMessage.textContent = "Errore del server";
    statusDesc.textContent = e;
  }
}

setInterval(updateIndicator, 1000);

