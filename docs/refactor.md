# Refactor di SafetyApp

Questo file contiene una lista di idee pensate per migliorare il backend SafetyApp

## Rimuovere le chiamate al DB in `requestSchoolStateJs.php`

### Problema

Essendo l'hot path principale di tutto il backend sarebbe preferibile non dover
effettuare una query al DB per ritornare sempre lo stesso valore.

In particolare:

- Lo script viene chiamato una volta ogni 2s da ogni LIM nell'istituto
- Lo script ritorna il 99.9% delle volte la stessa identica risposta
- La risposta è identica per tutti i client, cambia solamente quando viene
lanciato/disattivato un allarme
- Non ha senso salvare i dati su un DB quando i dati non sono neanche veramente
necessari da salvare su disco

### Soluzione

```php
<?php
header('Content-Type: application/json; charset=utf-8');
if ($contents = file_get_contents("/tmp/emergency")) {
  echo $contents;
} else {
  echo json_encode(
    ["STATO" => 0, "MESSAGGIO" => "No Pericolo", "DESCRIZIONE" => "tutto ok"]
  );
}
```

Ovvero la creazione del JSON della risposta dentro `set_alarm.php`, per poi salvarla
in `/tmp/emergency` (probabilmente non raggiungendo mai il disco!).

```php
file_put_contents('/tmp/emergency', json_encode([
    'STATO' => $stato,
    'MESSAGGIO' => $messaggio,
    'DESCRIZIONE' => $descrizione
]));
```

### Lavori preliminari

Al momento (05/02/2026) `set_alarm.php` riceve nel campo `id` l'id della tabella
allarmi da cui viene ricavata la variabile `$messaggio` dello snippet, lo script
però è ignaro di quale sarà il valore del campo `MESSAGGIO` nelle varie richieste.

La soluzione più rapida al problema è una query aggiuntiva al DB per ottenere il
messaggio dell'emergenza.

PERÒ, l'idea degli id non ha veramente senso:

## Rimuovere la convenzione degli `id` da `set_alarm.php`

### Problema

L'id degli allarmi viene unicamente utilizzato in `set_alarm.php` (e il suo
predecessore `gestisciallarme.php`):

- `requestSchoolStateJs.php` non usa la convenzione degli id allarme (dove erano
inizialmente concepiti da me)
- l'`id` deve essere mantenuto costante nel DB, altrimenti il pulsante con
l'Arduino deve essere riprogrammato (oppure riordinati gli sticker immagino)
- le richieste che inviano solo l'id non offrono la striga del messaggio facilmente
accessibile (il problema descritto nella [prima intestazione](#rimuovere-le-chiamate-al-db-in-requestschoolstatejsphp))

### Soluzione

Il pannello web, seppur potrebbe scegliere tra degli allarmi preimpostati, non
comporta problemi nella scrittura manuale del messaggio di allarme, pertanto
basterebbe aggiungere un campo `messaggio` (o `MESSAGGIO` per rispettare la
convenzione).

L'`id` è però necessario per l'Arduino, pertanto ci sono 3 soluzioni:

1. _Minimo cambiamento_: la tabella `Allarmi` rimane, il campo `id` viene
letto in assenza del campo `messaggio` e per il messaggio viene effettuata una
query al DB
2. _Rimozione del DB_: come sopra, ma la tabella `Allarmi` viene rimpiazzata da
un semplice file
3. _L'Arduino compenserà le sue mancanze_: scompare la tabella `Allarmi`, i
messaggi d'allarme sono hard-coded dentro l'Arduino (può scomparire il campo `id`)
