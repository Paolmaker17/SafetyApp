<?php
/*
 * Riceve una richiesta POST con parametri
 * - 'id': l'id dalla tabella 'Allarmi' del DB, altrimenti disattiva l'allarme
 *   ^ 07/01/2026 i valori accettati sono:
 *     1 (Allarme Terremoto)
 *     2 (Allarme Incendio)
 *     3 (Allarme Alluvione)
 *     4 (Allarme generico)
 * - 'desc': la descrizione da mostrare sui dispositivi, la linea sotto il tipo di allarme (stringa)
 */


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

$new_id = $_POST['id'];


include('dbconn.php');

if (isset($new_id)) {

    // Attivazione allarme: inserimento log
    $stmt = $conn->prepare(
        "INSERT INTO LOG (AMMINISTRATORE, DESCRIZIONE, FK_ID_ALLARME, STATO)
         VALUES (?, ?, ?, ?)"
    );

    if ($stmt === false) {
        echo json_encode([
            "message" => "Errore prepare: " . $conn->error
        ]);
        exit;
    }

    $amministratore = 'pulsante';
    $descrizione = isset($_POST['desc']) ? $_POST['desc'] : 'Allarme emergenza generica';
    $stato = 1;

    $stmt->bind_param(
        "ssii",
        $amministratore,
        $descrizione,
        $new_id,
        $stato
    );

} else {

    // Disattivazione allarme (più sicura di DELETE)
    $stmt = $conn->prepare(
        "UPDATE LOG SET STATO = 0 WHERE STATO = 1"
    );

    if ($stmt === false) {
        echo json_encode([
            "message" => "Errore prepare: " . $conn->error
        ]);
        exit;
    }
}

// Esecuzione query
if ($stmt->execute()) {
    echo json_encode([
        "message" => "Operazione riuscita",
        "id_allarme" => $new_id ?? null
    ]);
} else {
    echo json_encode([
        "message" => "Errore execute: " . $stmt->error
    ]);
}

$sql = "SELECT * FROM LOG join Allarmi on FK_ID_ALLARME = ID_ALLARME WHERE stato = 1 LIMIT 1";
$result = $conn->query($sql)->fetch_assoc();
file_put_contents('/tmp/emergency', json_encode([
    'STATO' => $result['STATO'],
    'MESSAGGIO' => $result['MESSAGGIO'],
    'DESCRIZIONE' => $descrizione
]));


$stmt->close();
$conn->close();

