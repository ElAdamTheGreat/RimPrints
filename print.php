<?php
$isSignedIn = false;

//print data
$id = 1;
$title = "Blueprint 1";
$desc = "Lorem Ipsum loremeeeeem";
$img = "https://placehold.co/300"; 
$content = ""; 
$createdAt = new DateTime("2024-12-10 15:39:00");
$updatedAt = "2020-01-01 00:00:00";
$userId = 1;

//user taht created this
$username = "username";

$currentDate = new DateTime();
$interval = $currentDate->diff($createdAt);
if ($interval->y > 0) {
    $relativeTime = $interval->y . " years ago";
} else if ($interval->m > 0) {
    $relativeTime = $interval->m . " months ago";
} else if ($interval->d > 0) {
    $relativeTime = $interval->d . " days ago";
} else if ($interval->h > 0) {
    $relativeTime = $interval->h . " hours ago";
} else if ($interval->i > 0) {
    $relativeTime = $interval->i . " minutes ago";
} else {
    $relativeTime = "Just now";
}

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
    <title><?php echo $title ?> - RimPrints</title>
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
                <h1><?php echo $title ?></h1>
                <p class="low-key"><?php echo $username ." · ". $relativeTime ?>  </p>
                <p><?php echo $desc ?></p>
            </div>
            <button class="btn-sm">Download print</button>
        </div>
    </div>
</body>
</html>