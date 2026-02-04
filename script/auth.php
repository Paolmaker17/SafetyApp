<?php
    include('dbconn.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        //recupero username e password
        $username = isset($_POST['username']) ? trim($_POST['username']) : ''; //IF isset, trim, else nothing ('')
        $password = isset($_POST['password']) ? trim($_POST['password']) : ''; //IF isset, trim, else nothing ('')
        //evitiamo XSS (cross site scripting)
        $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');
        //Verifichiamo che i campi non siano vuoti
        if(empty($username) || empty($password)){
            echo json_encode(
                array(
                    "status" => "error",
                    "messaggio" => "Auth: username o password vuoti"
                )
            );
            exit;
        }
        //Prepariamo query SQL
        $qry_str = "SELECT * FROM utenti WHERE username = '$username';";
        $stmt = $conn->query($qry_str);




        $num_righe = $conn->affected_rows;
        if($num_righe == 1){
            // OK ho letto un solo utente
            $row = $stmt -> fetch_assoc();
            $db_pass = $row["password"];
            $db_salt = $row["salt"];
            $crypted_pass = hash('sha512', $password.$db_salt);
            if($db_pass == $crypted_pass){
                //Autenticato
                //Registrare SESSIONE su server

                session_start();
                $_SESSION["autenticato"] = true;
                $_SESSION["username"] = $username;
                $_SESSION["time"] = time();
                header(header: 'Location:index.php');


            }else{
                //Non autenticato
                echo json_encode(
                    array(
                        "status" => "error",
                        "messaggio" => "Auth: Nome utente o password errato1."
                    )
                );
            }
        }else if($num_righe == 0){
            echo json_encode(
                array(
                    "status" => "error",
                    "messaggio" => "Auth: Nome utente o password non corretto."
                )
            );
        }else{
            echo json_encode(
                array(
                    "status" => "error",
                    "messaggio" => "Auth: più utenti con le stesse credenziali."
                )
            );
        }
    }else{
        header("Location:login.php");
        die();
    }
    ?>
