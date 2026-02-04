<?php
    $DB_SERVER = "127.0.0.1";
    // $DB_SERVER = "172.20.1.13";
    $DB_USERNAME = "root";
    $DB_PASSWORD = "Safety#App123_";
    $DB_NAME = "SAFETYAPP";

    $conn = null;
    $conn = new mysqli($DB_SERVER,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

    if(!$conn){
        echo json_encode(
            array(
                "status" => "error",
                "messaggio" => "Database: Errore connessione."
            )
        );
        die();
    }
?>
