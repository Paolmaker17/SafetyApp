// Gestione PopUp
let duration = 5000
let timer
let startTime
let remaining = duration

function startTimer() {
  startTime = Date.now()

  countdownBar.style.transition = "none"
  countdownBar.style.width = "100%"

  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      countdownBar.style.transition = `width ${remaining}ms linear`
      countdownBar.style.width = "0%"
    })
  })

  timer = setTimeout(closePopup, remaining)
}

function pauseTimer() {
  clearTimeout(timer)

  const elapsed = Date.now() - startTime
  remaining -= elapsed

  const percentLeft = (remaining / duration) * 100
  countdownBar.style.transition = "none"
  countdownBar.style.width = percentLeft + "%"
}

function resetTimer() {
  clearTimeout(timer)
  remaining = duration

  countdownBar.style.transition = "none"
  countdownBar.style.width = "100%"
}

function closePopup() {
  popUpWindow.classList.add("translate-y-6", "opacity-0", "scale-95")
  setTimeout(() => popUpWindow.style.display = "hidden", 300)
}

popUpWindow.addEventListener("mouseenter", () => {
  pauseTimer()
  resetTimer()
})
popUpWindow.addEventListener("mouseleave", () => {
  remaining = duration
  startTimer()
})
closePopupBut.addEventListener("click", closePopup)

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

async function stop_alarm() {
  const response = await fetch("set_alarm.php", {
    method: "POST",
    body: new URLSearchParams({}),
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

document.forms.inviaForm.onclick = async (ev) => {
  ev.preventDefault();
  const reponse = await set_alarm(
    document.getElementById("messaggioInput").value,
    document.getElementById("descInput").value
  )
  document.getElementById('popUpWindowText').textContent = response;
  setTimeout(() => {
    popUpWindow.classList.remove('translate-y-10', 'opacity-0')
  }, 100)
  startTimer()
};

stopAllarmBtn.onclick = () => { stop_alarm() }