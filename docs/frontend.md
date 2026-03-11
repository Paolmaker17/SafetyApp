# Come interagire dal frontend

## `auth.php`

> Verifica le credenziali e segna l'ID sessione PHP come autenticato

Accetta una richiesta del tipo

```HTTP
POST /auth.php HTTP/1
...

username=a&password=b
```

E risponde attraverso un

```HTTP
HTTP/1 200 OK
Location: index.html
...
```

in caso di autenticazione effettuata con successo

Oppure

```HTTP
HTTP/1 200 OK
...
<h1>Errore nell'autenticazione</h1>
<p> il messaggio di errore </p>
<a href='login.html'>Torna al login</a>
```

## `checkauth.php`

> Controlla che il session ID sia autenticato, altrimenti reindirizza a
> `login.html`

Da utilizzare negli script che necessitano di essere autenticati

```php
<?php include 'checkauth.php';?>
```

## `dbconn.php`

> Crea l'oggetto `mysqli` `$conn`, che gestisce la connessione al DB

```php
<?php
include 'dbconn.php';
$result = $conn->query("SELECT * FROM utenti");
echo json_encode($result->fetch_assoc());
?>
```
