<?php
session_start();
$isSignedIn = $_SESSION['isSignedIn'] ?? false;

$printId = $_GET['id'] ?? null;

include('server/queries.php');
include('components/loader/loader.php');
include('functions/relativeTime.php');

if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    $print = getPrintById($printId);
    $relCreatedAt = relativeTime($print->createdAt);
    $relUpdatedAt = relativeTime($print->updatedAt);

    header('Content-Type: application/json');
    echo json_encode([
        'title' => $print->title,
        'img' => "https://placehold.co/300",
        'relCreatedAt' => $relCreatedAt,
        //'createdAt' => $print->createdAt,
        'relUpdatedAt' => $relUpdatedAt,
        'username' => $print->user->username,
        'desc' => $print->desc
    ]);
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
    <script>
        const printId = '<?php echo $printId; ?>';
    </script>
    <script src="js/print.js" defer></script>
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
    <div id="content" class="content">
        <div class="col">
            <?php loader() ?>
            <h3 class="low-key">Loading data...</h3>
        </div>
    </div>
</body>
</html>