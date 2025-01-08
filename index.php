<?php
session_start();

include('server/queries.php');
include('components/loader/loader.php');
include('functions/getImagePath.php');

if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    $prints = getAll();

    header('Content-Type: application/json');
    $response = [];
    foreach ($prints as $print) {
        $imagePath = getImagePath($print->id);
        $response[] = [
            'id' => $print->id,
            'title' => $print->title,
            'img' => $imagePath,
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
    <script type="module" src="js/index.js" defer></script>
    <script type="module" src="js/universal.js" defer></script>
    <title>RimPrints</title>
</head>
<body>
    <nav class="nav">
        <a href="index.php" class="nav-title"><h1>R i m P r i n t s</h1></a>
        <div class="nav-links">
            <?php if (($_SESSION['isSignedIn'] ?? false) === true ) { ?>
                <a href="">Library</a>
                <a href=""><?php echo $_SESSION['username'] ?></a>
                <button class="link-button" id="signout-btn">Sign out</button>
            <?php } else { ?>
                <a href="signin.php">Sign in</a>
            <?php } ?>
        </div>
    </nav>
    <div class="content">
        <div class="heading">
            <h1>Community blueprints</h1>
            <button id="upload-btn" class="btn-sm">Upload</button>
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
    <div class="modal" id="modal-upload">
        <div class="modal-content">
            <h2>Login required</h2>
            <p>Unregistered users are not allowed to create prints. Please sign in to continue.</p>
            <div class="modal-buttons">
                <button class="btn-sm" id="modal1-close">Cancel</button>
                <a href="signin.php" class="btn-sm">Sign In</a>
            </div>
        </div>
    </div>
    <div class="modal" id="modal-signout">
        <div class="modal-content">
            <h2>Sign out?</h2>
            <p>Are you sire you want to sign out of your account?</p>
            <div class="modal-buttons">
                <button class="btn-sm" id="modal-signout-close">Cancel</button>
                <a href="signout.php" class="btn-sm-red">Sign out</a>
            </div>
        </div>
    </div>
</body>
</html>