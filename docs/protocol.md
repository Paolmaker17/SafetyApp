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
<Headers>
{
  "STATO": 1,
  "MESSAGGIO": "Incendio aula 333",
  "DESCRIZIONE": "Recarsi in ordine alle uscite di sicurezza",
}
```

Oppure

```http
HTTP/1.1 200 OK
<Headers>
{
  "STATO": 0
}
```

Oppure

```http
HTTP/1.1 200 OK
<Headers>
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
`header('Content-Type: application/json;');` all'inizio di ogni file che risponde
con JSON**

> Altrimenti l'interprete php farà l'escaping di caratteri come `&`, rompendo la
lettura
