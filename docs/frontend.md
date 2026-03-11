# Come interagire dal frontend

## `auth.php`

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