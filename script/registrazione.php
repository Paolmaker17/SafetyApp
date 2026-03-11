<?php
include('checkauth.php');
include_once('dbconn.php');

function sanitize($param): string
{
    if (!isset($_POST[$param]))
        throw new Exception("Auth: parametro $param vuoto");
    $param = htmlspecialchars(trim($_POST[$param]));
    if (empty($param))
        throw new Exception("Auth: parametro $param vuoto");
    return $param;
}


try {
    $username = sanitize("username");
    $password = sanitize("password");

    $stmt = $conn->execute_query("SELECT * FROM utenti WHERE username = ?", [$username]);

    if ($$conn->affected_rows !== 0)
        throw new Exception("Auth: Username già in uso");

    $salt = uniqid(mt_rand(1, mt_getrandmax()) . true);
    $crypted_salt = hash('sha512', $salt);
    $crypted_pass = hash('sha512', $password . $crypted_salt);
    $conn->execute_query(
        "INSERT INTO utenti (username, password, salt, tipo) VALUES (?, ?, ?, 'admin')",
        [$username, $crypted_pass, $crypted_salt]
    );
    header('Location:index.php');
} catch (Exception $e) {
    echo "<h1>Errore nella registrazione</h1>";
    echo "<p>{$e->getMessage()}</p>";
    echo "<a href='index.php'>Torna alla pagina principale</a>";
}
