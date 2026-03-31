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

function showToast(text) {
  document.getElementById('popUpWindowText').textContent = text;
  setTimeout(() => {
    popUpWindow.classList.remove('translate-y-10', 'opacity-0')
  }, 100)
  startTimer()
}

document.forms.inviaForm.onsubmit = async (ev) => {
  ev.preventDefault();
  const response = await set_alarm(
    document.getElementById("messaggioInput").value,
    document.getElementById("descInput").value
  )
  showToast(response);
};

stopAllarmeBtn.onclick = () => { stop_alarm() }

const addUserForm = document.getElementById("addUserForm");
const modifyPasswordForm = document.getElementById("modifyPasswordForm");
const usersTable = document.getElementById("usersTable");
addUserForm.onsubmit = async (ev) => {
  ev.preventDefault();

  let data = new FormData(addUserForm);
  data = {
    username: data.get("username"),
    password: data.get("password"),
    passwordConfirm: data.get("passwordConfirm")
  };

  if (!data.username || !data.password || !data.passwordConfirm) return;
  if (data.passwordConfirm != data.password) {
    showToast("Le password non coincidono");
    return;
  }

  const { passwordConfirm, ...request } = data;

  const response = await fetch("manage_users.php", {
    method: "PUT",
    headers: { 'Content-Type': "application/json" },
    body: JSON.stringify(request)
  }).then(it => it.text());

  addUserForm.querySelectorAll("input[name]").forEach(input => {
    input.value = ""
  })

  showToast(response);
  usersList();
}

modifyPasswordForm.onsubmit = async (ev) => {
  ev.preventDefault();

  let data = new FormData(modifyPasswordForm);
  data = {
    username: data.get("username"),
    password: data.get("password"),
    passwordConfirm: data.get("passwordConfirm")
  };

  if (!data.username || !data.password || !data.passwordConfirm) return;
  if (data.passwordConfirm != data.password) {
    showToast("Le password non coincidono");
    return;
  }

  const { passwordConfirm, ...request } = data;

  const response = await fetch("manage_users.php", {
    method: "PATCH",
    headers: { 'Content-Type': "application/json" },
    body: JSON.stringify(request)
  }).then(it => it.text());

  modifyPasswordForm.querySelectorAll("input[name]").forEach(input => {
    input.value = ""
  })

  showToast(response);
}

function el(type, ...children) {
  const elem = document.createElement(type);
  elem.append(...children);
  return elem;
}

function elClass(type, className, ...children) {
  const elem = el(type, ...children);
  elem.classList = className;
  return elem;
}

function elProps(type, props, ...children) {
  const elem = el(type, ...children);
  for (const n in props) {
    elem[n] = props[n];
  }
  return elem;
}

async function usersList(ev) {
  usersTable.innerHTML = '...';
  const response = await fetch("manage_users.php").then(it => it.json());
  usersTable.innerHTML = '';

  usersTable.append(
    ...response.map(user =>
      elClass("div", "border-t first:border-t-0 border-(--border) transition flex h-10 items-center",
        elClass("span", "font-semibold flex-1",
          user
        ),
        elProps("span",
          {
            classList: "px-2 py-1 text-xs rounded-full bg-red-500/10 text-red-500 border border-red-500/70 hover:bg-red-500/30",
            onclick: async () => {
              const response = await fetch("manage_users.php?" + new URLSearchParams({ username: user }),
                { method: "DELETE" }
              ).then(it => it.text());

              showToast(response);
              usersList();
            }
          },
          "Rimuovi"
        ),
      ),
    ),
  )
}

usersList();