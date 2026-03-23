<?php
$DB_SERVER = "127.0.0.1";
// $DB_SERVER = "172.20.1.13";
$DB_USERNAME = "root";
$DB_PASSWORD = "Safety#App123_";
$DB_NAME = "SAFETYAPP";

$conn = null;
$conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

function gen_salt(): string
{
    return hash('sha512', uniqid(mt_rand(1, mt_getrandmax()) . true));
}

function salt_pass(string $salt, string $pass): string
{
    return hash('sha512', $pass . $salt);
}

if (!$conn) {
    echo json_encode(
        array(
            "status" => "error",
            "messaggio" => "Database: Errore connessione."
        )
    );
    die();
}
