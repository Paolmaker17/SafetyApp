<?php
//inclusione script con le credenziali del db
include 'dbconn.php';

//recupero dei dati dallo script 'startAllarm.php'
//include 'startAllarm.php';
//$stato = startAllarm($conn);
//$emergenza = 1;



/*// Connessione al database
$servername = getServerName(); 
$username = getUserName(); 
$password = getPassword(); 
$dbname = getDatabase();

$conn = new mysqli($servername, $username, $password, $dbname);

// Controllo connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}*/

// Query per cercare nella tabella log la riga con stato = 1
$sql = "SELECT * FROM LOG join Allarmi on FK_ID_ALLARME = ID_ALLARME WHERE stato = 1 LIMIT 1";
$result = $conn->query($sql);

header('Content-Type: application/json; charset=utf-8');

// Controlla se è stata trovata una corrispondenza
if ($result->num_rows > 0) {
    // Preleva il record corrispondente
    $row = $result->fetch_assoc();

    // Restituisce il record come JSON
    echo htmlspecialchars_decode(json_encode($row));
} else {
    echo json_encode(["STATO" => 0, "MESSAGGIO" => "No Pericolo", "DESCRIZIONE" => "tutto ok"]);
}
// Chiudi la connessione
$conn->close();
?>
