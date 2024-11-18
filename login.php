<?php
$isSignedIn = false;
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="lib/favicon.ico" />
    <link rel="stylesheet" href="styles/universal.css">
    <link rel="stylesheet" href="styles/login.css">
    <title>Sign up - RimPrints</title>
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
        <form id="registration-form">
            <h3>Sign up to RimPrints</h3>
            <div class="form-sec">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-input">
            </div>
            <div class="form-sec">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-input">
            </div>
            <div class="form-sec">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input">
            </div>
            
            <span class="error">Error: Something went wrong.</span>
            
            <button type="submit" class="btn-sm">Sign Up</button>
        </form>
    </div>
</body>
</html>