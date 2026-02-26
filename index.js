const buttons = [
    document.querySelector("[data-name='invioAllarme']"),
    document.querySelector("[data-name='allarmi']"),
    document.querySelector("[data-name='utenti']")
]
const pages = [
    invioAllarme,
    allarmi,
    utenti
]

for (const b of buttons) {
    b.onclick = e => {
        buttons.forEach(b => b.disabled = false)
        b.disabled = true

        for(const p of pages) {
            p.style.display = p.id == b.dataset.name ? 'flex' : 'none'
        }
    }
}

// Returns the server response, should be shown to the user
async function set_alarm(idOrMessage, desc) {
    const response = await fetch(
        "set_alarm.php",
        {
            method: 'POST',
            body: new URLSearchParams(
                (typeof idOrMessage == 'string')
                    ? { id: 4 /* allarme generico */, message: idOrMessage, desc }
                    : { id: idOrMessage, desc }
            )
        }
    );
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
    return await response.json();
}