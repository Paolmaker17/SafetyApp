<?php
include 'dbconn.php';

date_default_timezone_set('Europe/Rome'); // Imposta il fuso orario per l'Italia
$data = date("Y-m-d");
$ora = date("H:i:s");

// Query per aggiornare il valore dell'allarme da 1 a 0
$sql = "UPDATE LOG SET stato = 0, DATA_MESSAGGIO =$data, ORA_MESSAGGIO=$ora WHERE stato = 1";

if ($conn->query($sql) === TRUE) {
    echo "Allarme resettato con successo.";
} else {
    echo "Errore nell'aggiornamento: " . $conn->error;
}

// Chiusura connessione
$conn->close();
?>
