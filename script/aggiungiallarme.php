
<?php include("checkauth.php"); ?>
<?php 
    include('dbconn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link href="form.css" rel="stylesheet" type="text/css">
    <title>Aggiungi allarme</title>
    <?php include("common/links.php");?>
</head>
<body>
<?php include("common/sidebar.php");?>
    <!-- struttura pagina unica con form e php (codice client e server insieme) -->

    <?php 

    if($_POST && isset($_POST['invia'])){
        //2° accesso pagina unica(form e php stesso file)
        if (array_key_exists("tipo", $_POST) && 
        array_key_exists("messaggio", $_POST)){ //array_key_exist è una funzione php che controlla se all'interno di un array
                                                                                                                    //in questo caso $_POST esiste la chiave 'dipartimento' e 'sede'
            //Recuper dei dati dal Form
            // $prog = $_POST['dipartimento'];
            $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING); //INPUT_POST indica di cercare sui dati della var POST, cerca la key dipartimento
            $messaggio = filter_input(INPUT_POST, 'messaggio', FILTER_SANITIZE_STRING);
            


            $qry_str = 'INSERT INTO Allarmi (TIPO,MESSAGGIO) VALUES ("'.$tipo.'","'.$messaggio.'")';
            if ($conn -> query($qry_str) === TRUE){
                echo "Record Inserito Correttamente";
            }else{
                echo "Errore inserimento record. <br>Errore: " . $conn->error;
            }

        }else{
            //Errore di ricezione dei dati
            echo "Errore invia";
        }

    }else{
        //1° accesso

    ?>
    <div class="content mt-5">
    <!-- <div class="d-flex align-items-center justify-content-center mt-5"> -->
        <div>
            <h3>Inserimento nuovo tipo di Allarme</h3>
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                
                <label for="tipo">Tipo Allarme: </label>
                <input type="text" name="tipo" id="tipo"> <br>
                
                <label for="messaggio">Messaggio Allarme: </label>
                <input type="text" name="messaggio" id="messaggio"> <br>
                
                <input type="submit" value="Invia" name="invia">
                <input type="reset" value="reset" name="reset">
        
            </form>
        </div>
    </div>


    <?php

    }

    ?>
    <script src="JS.js"></script>
</body>
</html>


