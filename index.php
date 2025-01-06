<?php
session_start();
$_SESSION['isSignedIn'] = false;

include('server/queries.php');
include('components/loader/loader.php');

if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    $prints = getAll();

    header('Content-Type: application/json');
    $response = [];
    foreach ($prints as $print) {
        $response[] = [
            'id' => $print->id,
            'title' => $print->title,
            'img' => "https://placehold.co/300",
            'username' => $print->user->username,
        ];
    }
    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="lib/favicon.ico" />
    <link rel="stylesheet" href="styles/universal.css">
    <link rel="stylesheet" href="styles/index.css">
    <script src="js/index.js" defer></script>
    <title>RimPrints</title>
</head>
<body>
    <nav class="nav">
        <a href="index.php" class="nav-title"><h1>R i m P r i n t s</h1></a>
        <div class="nav-links">
            <a href="">Library</a>
            <?php if (($_SESSION['isSignedIn'] ?? false) === true ) { ?>
                <a href="">Account</a>
            <?php } else { ?>
                <a href="signin.php">Sign in</a>
            <?php } ?>
        </div>
    </nav>
    <div class="content">
        <div class="heading">
            <h1>Community blueprints</h1>
            <a href="upload.php" class="btn-sm">Upload</a>
        </div>

        <div class="data" id="data">
            <div class="center col">
                <?php loader() ?>
                <h3>Loading data...</h3>
            </div>
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