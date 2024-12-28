<?php
session_start();
$isSignedIn = $_SESSION['isSignedIn'] ?? false;

include('server/queries.php');
include('components/loader/loader.php');

if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    $title = $_POST['title'];
    $desc = $_POST['desc'];
    $xmlContent = $_POST['xmlContent'];
    $img = $_FILES['pic']['name'];
    $userId = $_SESSION['userId'] ?? 1; // Replace with actual user ID from session

    $newPrintId = createPrint($title, $desc, 1, $xmlContent, $userId);
    header('Content-Type: application/json');
    echo json_encode(['id' => $newPrintId]);

    // Save the image file
    $imgExtension = pathinfo($img, PATHINFO_EXTENSION);
    $newImgName = $newPrintId . '.' . $imgExtension;
    move_uploaded_file($_FILES['pic']['tmp_name'], "lib/img/" . $newImgName);

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
    <link rel="stylesheet" href="styles/upload.css">
    <script src="js/upload.js" defer></script>
    <title>Upload Blueprint - RimPrints</title>
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
    <div class="content" id="content">
        <h1>Upload Blueprint</h1>
        <form id="upload-form" class="form" method="post" enctype="multipart/form-data">
            <div class="form-section">
                <div class="form-data">
                    <div class="form-sec">
                        <label for="title" class="h3">Blueprint title</label>
                        <input type="text" class="form-input" id="title" name="title" required>
                    </div>
                    <div class="form-sec">
                        <label for="desc" class="h3">Description</label>
                        <textarea class="form-input" id="desc" name="desc" rows="5" required></textarea>
                    </div>
                    <input type="file" class="file" name="file" id="file" accept=".xml" required>
                </div>
                <div class="vert-line"></div>
                <div class="form-picture">
                    <img src="https://placehold.co/300" alt="placeholder">
                    <input type="file" class="file" name="pic" id="pic" accept="image/png, image/jpeg">
                </div>
            </div>
            <button type="submit" class="btn-sm" id="submit">Upload</button>
        </form>
    </div>
</body>
</html>