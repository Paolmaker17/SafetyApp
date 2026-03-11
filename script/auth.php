<?php

include 'dbconn.php';

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

    $username = sanitize('username');
    $password = sanitize('password');

    if (empty($username) || empty($password))
        throw new Exception("Auth: username o password vuoti");

    $stmt = $conn->execute_query("SELECT * FROM utenti WHERE username = ?", [$username]);

    // Controlla se è presente l'username
    switch ($conn->affected_rows) {
        case 1:
            break;
        case 0:
            throw new Exception("Auth: Nome utente o password non corretto.");
        default:
            throw new Exception("Auth: più utenti con le stesse credenziali.");
    }

    $row = $stmt->fetch_assoc();
    $db_pass = $row["password"];
    $db_salt = $row["salt"];
    $crypted_pass = hash('sha512', $password . $db_salt);

    if ($db_pass != $crypted_pass)
        throw new Exception("Auth: Nome utente o password non corretto.");

    // Autenticato!
    // Registrare SESSIONE su server
    session_start();
    $_SESSION["autenticato"] = true;
    $_SESSION["username"] = $username;
    $_SESSION["time"] = time();
    header(header: 'Location:index.php');
    exit(200);
} catch (Exception $e) {
    echo "<h1>Errore nell'autenticazione</h1>";
    echo "<p>{$e->getMessage()}</p>";
    echo "<a href='login.html'>Torna al login</a>";
}
?>