<?php
session_start();
// is user not signed in?
if (!($_SESSION['isSignedIn'] ?? false)) {
    header('Location: index.php');
    exit;
}

include('server/queries.php');
include('components/loader/loader.php');

if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    $title = $_POST['title'];
    $desc = $_POST['desc'];
    $xmlContent = $_POST['xmlContent'];
    $img = $_FILES['pic']['name'];
    $userId = $_SESSION['userId'] ?? 1;

    $newPrintId = createPrint($title, $desc, $xmlContent, $userId);
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
    <script type="module" src="js/universal.js" defer></script>
    <title>Upload Blueprint - RimPrints</title>
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
                        <textarea class="form-input" id="desc" name="desc" rows="10" required></textarea>
                    </div>
                    <div class="form-sec">
                        <label for="file" class="h3">Blueprint file (.xml)</label>
                        <input type="file" class="file" name="file" id="file" accept=".xml" required>
                    </div>
                </div>
                <div class="vert-line"></div>
                <div class="form-picture">
                    <label for="pic" class="h3">Blueprint picture</label>
                    <img src="https://placehold.co/300" alt="preview" id="preview" width="300">
                    <input type="file" class="file" name="pic" id="pic" accept="image/png, image/jpeg">
                </div>
            </div>
            <button type="submit" class="btn-sm" id="submit">Upload</button>
        </form>
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