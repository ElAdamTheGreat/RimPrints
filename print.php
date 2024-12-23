<?php
session_start();
$isSignedIn = $_SESSION['isSignedIn'] ?? false;

include('server/queries.php');
$print = getPrintById(1);

//print data
$img = "https://placehold.co/300"; 

include('functions/relativeTime.php');
$relCreatedAt = relativeTime(new DateTime($print->createdAt));
$relUpdatedAt = relativeTime(new DateTime($print->updatedAt));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="lib/favicon.ico" />
    <link rel="stylesheet" href="styles/universal.css">
    <link rel="stylesheet" href="styles/print.css">
    <script src="js/script.js" defer></script>
    <title><?php echo $print->title ?> - RimPrints</title>
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
        <div class="picture">
            <img src="https://placehold.co/500" alt="placeholder">
        </div>
        <div class="vert-line"></div>
        <div class="section">
            <div class="data">
                <h1><?php echo $print->title ?></h1>
                <p class="low-key"><?php echo $print->user->username ." · ". $relCreatedAt ?>  </p>
                <p><?php echo $print->desc ?></p>
            </div>
            <button class="btn-sm">Download print</button>
        </div>
    </div>
</body>
</html>