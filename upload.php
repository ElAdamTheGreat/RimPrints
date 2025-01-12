<?php
/**
 * This file handles the upload process for blueprints.
 * It manages AJAX requests for uploading blueprint details and images.
 * 
 * @author Adam Gombos
 */

session_start();
/**
 * Check if the user is signed in
 */ 
if (!($_SESSION['isSignedIn'] ?? false)) {
    header('Location: index.php');
    exit;
}

include('server/queries.php');
include('components/loader/loader.php');

/**
 * Handle AJAX request to upload a new blueprint
 */
if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    $title = $_POST['title'];
    $desc = $_POST['desc'];
    $xmlContent = $_POST['xmlContent'];
    $img = $_FILES['pic']['name'] ?? 'not-provided';
    $userId = $_SESSION['userId'] ?? 1;

    /**
     * Validate data
     */
    if ((strlen($title) > 32 || strlen($title) < 1) || strlen($desc) > 512 || strlen($xmlContent) > 1048576) {
        echo json_encode(['error' => '422']);
        exit;
    }

    $newPrintId = createPrint($title, $desc, $xmlContent, $userId);
    header('Content-Type: application/json');
    echo json_encode(['id' => $newPrintId]);

    // Increase the prints counter
    $_SESSION['prints'] = $_SESSION['prints'] + 1;

    /**
     * Save the image file
     */
    if ($img === 'not-provided') {
        exit;
    }
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
    <script type="module" src="js/upload.js" defer></script>
    <script type="module" src="js/universal.js" defer></script>
    <title>Upload Blueprint - RimPrints</title>
</head>
<body>
    <nav class="nav">
        <a href="index.php" class="nav-title"><h1>R i m P r i n t s</h1></a>
        <a href="index.php" class="nav-title-mobile"><h1>R</h1></a>
        <div class="nav-links">
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                <a href="admin.php">Administration</a>
            <?php endif; ?>
            <?php if (($_SESSION['isSignedIn'] ?? false) === true ) { ?>
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
                        <label for="title" class="h3">Blueprint title<span class="required">*</span></label>
                        <input type="text" class="form-input" id="title" name="title">
                        <span id="error-title" class="error"></span>
                    </div>
                    <div class="form-sec">
                        <label for="desc" class="h3">Description</label>
                        <textarea class="form-input" id="desc" name="desc" rows="10"></textarea>
                        <span id="error-desc" class="error"></span>
                    </div>
                    <div class="form-sec">
                        <label for="file" class="h3">Blueprint file<span class="required">*</span> (.xml)</label>
                        <input type="file" class="file" name="file" id="file" accept=".xml">
                        <span id="error-file" class="error"></span>
                        <button id="whereprints-btn" class="link-button-blue responsive-text">Where do I find my blueprints?</button>
                    </div>
                </div>
                <div class="vert-line"></div>
                <div class="form-picture">
                    <label for="pic" class="h3">Blueprint picture</label>
                    <img src="https://placehold.co/300" alt="preview" id="preview">
                    <input type="file" class="file" name="pic" id="pic" accept="image/png, image/jpeg">
                    <span id="error-pic" class="error"></span>
                </div>
            </div>
            <button type="submit" class="btn" id="submit">Upload</button>
        </form>
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
    <div class="modal" id="modal-whereprints">
        <div class="modal-content">
            <h2>Where do I find my blueprints?</h2>
            <div>
                <p>Good question! You can do it like this:</p>
                <h4>Export Blueprint</h4>
                <ol>
                    <li>Open the game</li>
                    <li>Right-click on the blueprint you want to upload</li>
                    <li>Click on "Export"</li>
                </ol>
                <h4>Locate file</h4>
                <ol>
                    <li>Open game settings</li>
                    <li>Open "Log file folder"</li>
                    <li>In newly opened file explorer open folder named "Blueprints"</li>
                    <li>Select your desired blueprint</li>
                </ol>
                <p class="low-key small-text">C:\Users\&lt;user&gt;\AppData\LocalLow\Ludeon Studios\RimWorld by Ludeon Studios\Blueprints</p>
            </div>
            <button class="btn" id="modal-whereprints-close">Ok</button>
        </div>
    </div>
</body>
</html>