# Utilizzare manage_users.php

NOTA: Per tutti gli script è necessario essere già autenticati

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