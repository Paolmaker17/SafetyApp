<?php
    include('dbconn.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        //recupero username e password
        $username = isset($_POST['username']) ? trim($_POST['username']) : ''; //IF isset, trim, else nothing ('')
        $password = isset($_POST['password']) ? trim($_POST['password']) : ''; //IF isset, trim, else nothing ('')
        $confpassword = isset($_POST['confpassword']) ? trim($_POST['confpassword']) : ''; //IF isset, trim, else nothing ('')
        //evitiamo XSS (cross site scripting)
        $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');
        $confpassword = htmlspecialchars($confpassword, ENT_QUOTES, 'UTF-8');
        //Verifichiamo che i campi non siano vuoti
        if(empty($username) || empty($password || empty($confpassword))){
            echo json_encode(
                array(
                    "status" => "error",
                    "messaggio" => "Auth: username o password vuoti"
                )
            );
            exit;
        }elseif($password != $confpassword){
            echo json_encode(
                array(
                    "status" => "error",
                    "messaggio" => "Auth: Le password non coincidono"
                )
            );
            exit;
        }
        //Prepariamo query SQL
        $qry_str = "SELECT * FROM utenti WHERE username = '$username';";
        $stmt = $conn->query($qry_str);
        $num_righe = $conn->affected_rows;
        if($num_righe == 0){
            $salt = uniqid(mt_rand(1,mt_getrandmax()). true);
            $crypted_salt = hash('sha512' , $salt);
            $crypted_pass = hash('sha512', $password.$crypted_salt);
            $qry_str = "INSERT INTO utenti (username, password, salt, tipo) VALUES ('$username', '$crypted_pass','$crypted_salt','admin')";
            $conn->query($qry_str);
                 header('Location:index.php');
            }elseif($num_righe==1){
                echo json_encode(
                    array(
                        "status" => "error",
                        "messaggio" => "Auth: Utente esistente"
                    )
                );
            }else{
                //Non autenticato
                echo json_encode(
                    array(
                        "status" => "error",
                        "messaggio" => "Auth: username utente o password errato."
                    )
                );
            }
        }else if($num_righe == 0){
            echo json_encode(
                array(
                    "status" => "error",
                    "messaggio" => "Auth: username utente o password non corretto."
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

    ?>
