<?php
$isSignedIn = false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="lib/favicon.ico" />
    <link rel="stylesheet" href="styles/universal.css">
    <link rel="stylesheet" href="styles/index.css">
    <title>RimPrints</title>
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
        <div class="heading">
            <h1>Community blueprints</h1>
            <button class="btn-sm">Upload</button>
        </div>
        <div class="printgrid">
            <?php
            for ($i = 1; $i <= 30; $i++) {
                echo '
                <div class="card">
                    <img src="https://placehold.co/300" alt="placeholder">
                    <h2>Blueprint ' . $i . '</h2>
                    <div class="cardinfo">
                        <p class="low-key">Author name</p>
                        <p>15</p>
                    </div>
                </div>';
            }
            ?>
        </div>


        <div class="alert">
            <p>How do I upload?</p>
        </div>
    </div>
</body>
</html>