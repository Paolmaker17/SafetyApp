<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="form.css" rel="stylesheet" type="text/css">
    <title>SafetyApp</title>
    <?php include("common/links.php");?>
</head>
 

<!-- INIZIO FORM LOGIN -->
<body class="bg-light">

    <div class="login-container">

        <h2 class="text-center mb-4">Accedi a SafetyApp  <br>IIS Fermi Sacconi Ceci Cpia</h2>
  

        <div class="text-center">
            <img src="SafetyApp.png" alt="Immagine Safety App" width="30%" heigth="30%">
        </div>

        <form action="auth.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username: </label>
                <input type="username" name="username" placeholder="username" class="form-control"><br>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password: </label>
                <input type="password" name="password" placeholder="Password" class="form-control"><br>
            </div>
            
            
            <?php 
        if(isset($error)) : ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <input type="submit" class="btn btn-primary" value="Accedi">
        </form>
    </div>
    <!-- FINE FORM LOGIN -->
</body>
</html>
