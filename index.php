<?php
session_start();
$isSignedIn = $_SESSION['isSignedIn'] ?? false;

include('server/queries.php');
$prints = getAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="lib/favicon.ico" />
    <link rel="stylesheet" href="styles/universal.css">
    <link rel="stylesheet" href="styles/index.css">
    <script src="js/script.js" defer></script>
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
            <a href="upload.php" class="btn-sm">Upload</a>
        </div>
        <div class="printgrid">
            <?php foreach ($prints as $print) { ?>
                <a href="print.php" class="card">
                    <img src="https://placehold.co/300" alt="placeholder">
                    <h2><?php echo $print->title ?></h2>
                    <div class="cardinfo">
                        <p class="low-key">by <?php echo $print -> user -> username ?></p>
                        <p>WIP</p>
                    </div>
                </a>
            <?php } ?>
        </div>


        <div class="alert">
            <p>How do I upload?</p>
        </div>
    </div>
    <div class="modal" id="modal">
        <div class="modal-content">
            <h2>Modal Title</h2>
            <p>This is a modal.</p>
            <button class="btn-sm" id="modal-close">Close</button>
        </div>
    </div>
</body>
</html>