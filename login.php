<?php
$isSignedIn = false;
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/universal.css">
    <link rel="stylesheet" href="styles/login.css">
    <title>Sign up</title>
</head>
<body>
    <nav class="nav">
        <a href="index.php" class="nav-title"><h1>R i m P r i n t s</h1></a>
        <div class="nav-links">
            <a href="">Library</a>
            <?php if ($isSignedIn) { ?>
                <a href="">Account</a>
            <?php } else { ?>
                <a href="login.php">Log in</a>
            <?php } ?>
        </div>
    </nav>
    <div class="content">
        <h3>Sign up to RimPrints</h3>
        <form id="registration-form">
            <label for="username">Username</label>
            <input type="text" id="username" name="username">
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email">
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password">
            
            <span class="error">Error: Something went wrong.</span>
            
            <button type="submit" class="btn-sm">Odeslat</button>
        </form>
    </div>
</body>
</html>