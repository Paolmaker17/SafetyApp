<?php
include_once('dbconn.php');
include('checkauth.php');

function sanitize(array $from, string $param): string
{
    if (!isset($from[$param]))
        throw new Exception("Auth: parametro $param vuoto");
    $val = htmlspecialchars(trim($from[$param]));
    if (empty($val))
        throw new Exception("Auth: parametro $param vuoto");
    return $val;
}

try {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            header('Content-Type: application/json');
            $result = $conn->query("SELECT username FROM utenti");
            $val = $result->fetch_all(MYSQLI_ASSOC);
            $val = array_map(function ($el) {
                return $el['username']; }, $val);
            echo json_encode($val);
            break;
        case 'PATCH':
        case 'PUT':
            $req = json_decode(file_get_contents('php://input'), true);
            $username = sanitize($req, "username");
            $password = sanitize($req, "password");

            $salt = gen_salt();
            $pass = salt_pass($salt, $password);

            $stmt = $conn->prepare(
                $_SERVER['REQUEST_METHOD'] == 'PATCH'
                ? "UPDATE utenti SET password = ?, salt = ? WHERE username = ?"
                : "INSERT INTO utenti(password, salt, username, tipo) VALUES (?, ?, ?, 'admin')"
            );
            $ok = $stmt->execute([$pass, $salt, $username]);

            if (!$ok)
                throw new Exception("Errore nell'operazione");

            if ($stmt->affected_rows < 1)
                throw new Exception("Errore: nessun utente trovato");

            throw new Exception(
                $_SERVER['REQUEST_METHOD'] == 'PATCH'
                ? "Password modificata"
                : "Utente aggiunto con successo"
            );
        case 'DELETE':
            // $_GET non è riservato alle richieste GET
            // https://www.php.net/manual/en/reserved.variables.get.php
            $username = sanitize($_GET, 'username');

            $stmt = $conn->prepare("DELETE FROM utenti WHERE username = ?");
            $stmt->execute([$username]);

            if ($stmt->affected_rows < 1) {
                throw new Exception("Errore: nessun utente trovato");
            }

            throw new Exception("Utente rimosso con successo");
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

