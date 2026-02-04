<?php include("checkauth.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="form.css" rel="stylesheet" type="text/css">
    <title>Register</title>
    <?php include("common/links.php");?>
</head>

<!-- INIZIO FORM LOGIN -->
<body>
    <div class="login-container">
        <h2 class="text-center mb-4">Registra utente</h2>
        <form action="registrazione.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username: </label>
                <input type="username" name="username" placeholder="username" class="form-control"><br>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password: </label>
                <input type="password" name="password" placeholder="Password" class="form-control"><br>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Conferma Password: </label>
                <input type="password" name="confpassword" placeholder="Conferma Password" class="form-control"><br>
            </div>
            
            
            <?php 
        if(isset($error)) : ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <input type="submit"  value="Registrati">
        </form>
    </div>
    <!-- FINE FORM LOGIN -->
    <script src="JS.js"></script>
</body>
</html>
