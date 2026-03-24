# Utilizzare manage_users.php

NOTA: Per tutti gli script è necessario essere già autenticati

NOTA 2: Tutti gli script ritornano una stringa con l'esito dell'operazione, eccetto GET

## Lista

```http
GET /safetyApp/manage_users.php HTTP/1.1
```

Con risposta tipo

```http
HTTP/1.1 200 OK
...

[
    "user1",
    "user2"
]
```

## Aggiunta

```http
PUT /safetyApp/manage_users.php HTTP/1.1
Content-Type: application/json
...

{
    "username": "usernameDellUtenteDaCreare",
    "password": "passwordDellUtenteDaCreare"
}
```

## Rimozione

```http
DELETE /safetyApp/manage_users.php?username=usernameDellUtente HTTP/1.1
```

## Reset password

```http
PATCH /safetyApp/manage_users.php HTTP/1.1
Content-Type: application/json
...

{
    "username": "username dell'utente",
    "password": "la nuova password"
}
```