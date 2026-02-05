# Protocollo SafetyApp

Ultimo update: 05/02/2026
Da: Amedeo Alfonsi

## Fondamenta

- Tutte le richieste avvengono tramite HTTP al server 172.20.1.13, tramite JSON
- Gli allarmi sono salvati nel Database (tabella LOG)
- Gli allarmi devono essere autenticati, mentre il controllo dello stato dell'allarme
no

## Controllare lo stato di emergenza attuale

I client controllano lo stato mandando una richiesta del tipo:

```http
POST /safetyApp/requestSchoolStateJs.php HTTP/1.1
```

E ricevono dal server una risposta formattata:

```http
HTTP/1.1 200 OK
...

{
  "STATO": 1,
  "MESSAGGIO": "Incendio aula 333",
  "DESCRIZIONE": "Recarsi in ordine alle uscite di sicurezza",
}
```

Oppure

```http
HTTP/1.1 200 OK
...

{
  "STATO": 0
}
```

Oppure

```http
HTTP/1.1 200 OK
...

{
  "ERROR": "Impossibile accedere al db"
}
```

### NOTE

1. **Il server ad un certo punto nello sviluppo ha smesso di ritornare gli errori
nel campo "ERROR"**

Bensì ritorna un messaggio del tipo:

```json
{
  "status": "error",
  "messaggio": "Database: Errore connessione",
}
```

Che (per puro caso) mostra lo stesso un errore sul client per via di quello che
può essere definito un bug nella funzione

```kotlin
fun JSONObject.getIntOrNull(key: String /* "STATO" in questo caso */ ): Int? {
    return if (has(key)) getInt(key) else null
}
```

E [`JSONObject.getInt()`](https://developer.android.com/reference/org/json/JSONObject#getInt(java.lang.String))
lancia un'eccezione quando il valore richiesto non è un intero

2. **L'implementazione attuale di `requestSchoolStateJs.php` ritorna molti più parametri
di quanti descritti, solo che né il client android né windows li utilizzano veramente**

3. **Il comportamento del client descritto è definito in [questo file](https://github.com/AmedeoAlf/SafetyApp/blob/8e6eab5a2fe6c0b012a7d5c60e436e067229a5f9/app/src/main/java/it/edu/iisfermisacconiceciap/safetyapp/EmergencyState.kt)**

4. **Per i prossimi che materranno gli script php: RICORDATE DI AGGIUNGERE
`header('Content-Type: application/json');` all'inizio di ogni file che risponde
con JSON**

> Altrimenti l'interprete php farà l'escaping di caratteri come `&`, rompendo la
lettura

## Dare l'allarme

Per scatenare un allarme è necessaria una richiesta:

```http
POST /safetyApp/set_alarm.php HTTP/1.1
Content-Type: "application/json"
...

{
  "id": 2,
  "desc": "Recarsi in sicurezza alle uscite di emergenza"
}
```

Oppure (per disattivare l'allarme)

```http
POST /safetyApp/set_alarm.php HTTP/1.1
Content-Type: "application/json"
...

{}
```

Dove:

- `id` è l'indice nella tabella `Allarmi` (imposta quindi indirettamente il
`MESSAGE` per `requestSchoolStateJs.php`), **non impostare il campo significa
disattivare l'allarme**
- `desc` è il valore del campo `DESCRIZIONE`, può essere omesso (ma viene
rimpiazzato da una stringa non ideale, al momento: _Allarme emergenza generica_)

La rispota del server sarà poi

```http
HTTP/1.1 200 OK
...

{
  "message": "Operazione riuscita",
  "id_allarme": 2
}
```

Oppure (per la disattivazione dell'allarme)

```http
HTTP/1.1 200 OK
...

{
  "message": "Operazione riuscita",
  "id_allarme": null
}
```

Oppure (in caso di errori)

```http
HTTP/1.1 200 OK
...

{
  "message": "Error execute: errore probabilmente in inglese di mysqli"
}
```

### NOTE

1. `set_alarm.php` al momento è utilizzato soltanto dall'arduino, ma il suo obiettivo
è rimpiazzare `gestisciallarme.php` e `resetAllarm.php` in futuro.
