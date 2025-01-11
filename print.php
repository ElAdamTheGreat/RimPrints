<?php
session_start();
$printId = $_GET['id'] ?? null;

if ($printId === null || !is_numeric($printId)) {
    header('Location: index.php');
    exit;
}

include('server/queries.php');
include('components/loader/loader.php');
include('functions/relativeTime.php');
include('functions/getImagePath.php');

if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    $print = getPrintById($printId);
    $imagePath = getImagePath($printId);

    if (!$print || !$imagePath) {
        echo json_encode(['error' => '404']);
        exit;
    }

    $relCreatedAt = relativeTime($print->createdAt);
    $relUpdatedAt = relativeTime($print->updatedAt);
    $printActions = isset($_SESSION['isSignedIn']) && $_SESSION['isSignedIn'] === true && ($print->user->id === $_SESSION['userId'] || $_SESSION['role'] === 'admin');
    header('Content-Type: application/json');
    echo json_encode([
        'title' => htmlspecialchars($print->title),
        'desc' => htmlspecialchars($print->desc),
        'content' => $print->content,
        'img' => htmlspecialchars($imagePath),
        'relCreatedAt' => $relCreatedAt,
        //'createdAt' => $print->createdAt,
        'relUpdatedAt' => $relUpdatedAt,
        'username' => htmlspecialchars($print->user->username),
        'showActions' => $printActions,
    ]);
    exit;
}

if (isset($_GET['ajax']) && $_GET['ajax'] == 2) {
    $result = deletePrint($printId);
    if ($result === true) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
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
    <link rel="stylesheet" href="styles/print.css">
    <link rel="stylesheet" href="components/loader/loader.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=keyboard_arrow_down" />
    <script>
        const printId = '<?php echo $printId; ?>';
    </script>
    <script type="module" src="js/print.js" defer></script>
    <script type="module" src="js/universal.js" defer></script>
    <title><?php echo htmlspecialchars($print->title) ?> - RimPrints</title>
</head>
<body>
<nav class="nav">
        <a href="index.php" class="nav-title"><h1>R i m P r i n t s</h1></a>
        <div class="nav-links">
            <?php if (($_SESSION['isSignedIn'] ?? false) === true ) { ?>
                <a href=""><?php echo htmlspecialchars($_SESSION['username']) ?></a>
                <button class="link-button" id="signout-btn">Sign out</button>
            <?php } else { ?>
                <a href="signin.php">Sign in</a>
            <?php } ?>
        </div>
    </nav>
    <div id="content" class="content">
        <div class="col">
            <?php loader() ?>
            <h3 class="low-key">Loading data...</h3>
        </div>
    </div>
    <div class="modal" id="modal-signout">
        <div class="modal-content">
            <h2>Sign out?</h2>
            <p>Are you sure you want to sign out of your account?</p>
            <div class="modal-buttons">
                <button class="btn" id="modal-signout-close">Cancel</button>
                <a href="signout.php" class="btn-red">Sign out</a>
            </div>
        </div>
    </div>
    <div class="modal" id="modal-printDelete">
        <div class="modal-content">
            <h2>Delete print?</h2>
            <p>Are you sure you want to delete this print? This action is irreversible.</p>
            <div class="modal-buttons" id="delete-buttons">
                <button class="btn" id="modal-printDelete-close">Cancel</button>
                <button class="btn-red" id="modal-printDelete-confirm">Delete</button>
            </div>
        </div>
    </div>
    <div class="modal" id="modal-whereprints">
        <div class="modal-content">
            <h2>Where do I put blueprints?</h2>
            <div>
                <p>Good question! You can do it like this:</p>
                <h4>Blueprint folder</h4>
                <ol>
                    <li>Download blueprint</li>
                    <li>Open RimWorld > settings</li>
                    <li>Open "Log file folder"</li>
                    <li>In newly opened file explorer open folder named "Blueprints"</li>
                    <li>Put the blueprint you just downloaded here</li>
                </ol>
                <p class="low-key small-text">C:\Users\&lt;user&gt;\AppData\LocalLow\Ludeon Studios\RimWorld by Ludeon Studios\Blueprints</p>
                <h4>Import blueprint</h4>
                <ol>
                    <li>Load your save in RimWorld</li>
                    <li>Right-click on "Create" button in blueprint section</li>
                    <li>Click on "Import blueprint &lt;title&gt;"</li>
                </ol>
            </div>
            <button class="btn" id="modal-whereprints-close">Ok</button>
        </div>
    </div>
</body>
</html>