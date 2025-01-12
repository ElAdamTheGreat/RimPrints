<?php
session_start();
$printId = $_GET['id'] ?? null;
if ($printId === null || !is_numeric($printId)) {
    header('Location: index.php');
    exit;
}

include('server/queries.php');
include('components/loader/loader.php');
include('functions/getImagePath.php');

if (isset($_GET['ajax'])) {
    if ($_GET['ajax'] == 1) {
        $print = getPrintById($printId);
        $imagePath = getImagePath($printId);

        if (!$print || !$imagePath) {
            echo json_encode(['error' => '404']);
            exit;
        }
        
        if (!($_SESSION['isSignedIn'] ?? false) || ($print->user->id !== $_SESSION['userId'] && $_SESSION['role'] !== 'admin')) {
            echo json_encode(['error' => '401']);
            exit;
        }

        header('Content-Type: application/json');
        echo json_encode([
            'title' => $print->title,
            'desc' => $print->desc,
            'content' => $print->content,
            'img' => $imagePath,
            'userId' => $print->user->id,
            'username' => $print->user->username,
        ]);
        exit;
    }

    if ($_GET['ajax'] == 2) {
        $title = $_POST['title'];
        $desc = $_POST['desc'];
        $xmlContent = $_POST['xmlContent'];
        $img = $_FILES['pic']['name'] ?? 'not-provided';

        // validate data
        if (strlen($title) > 32 || strlen($desc) > 512 || strlen($xmlContent) > 1048576) {
            echo json_encode(['error' => '422']);
            exit;
        }

        updatePrint($printId, $title, $desc, $xmlContent);
        header('Content-Type: application/json');
        echo json_encode(['id' => $printId]);

        if ($img === 'not-provided') {
            exit;
        }
        // REPLACE the image file
        $imgPath = getImagePath($printId);
        if ($imgPath !== 'lib/img/placeholder.png') {
            unlink($imgPath);
        }
        $imgExtension = pathinfo($img, PATHINFO_EXTENSION);
        $newImgName = $printId . '.' . $imgExtension;
        move_uploaded_file($_FILES['pic']['tmp_name'], "lib/img/" . $newImgName);
        exit;
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
    <link rel="stylesheet" href="styles/upload.css">
    <script>
        const printId = '<?php echo $printId; ?>';
    </script>
    <script type="module" src="js/edit.js" defer></script>
    <script type="module" src="js/universal.js" defer></script>
    <title>Edit Blueprint - RimPrints</title>
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
        <h1>Edit Blueprint</h1>
        <form id="edit-form" class="form" method="post" enctype="multipart/form-data">
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
                        <label for="file" class="h3">Blueprint file (.xml)</label>
                        <input type="file" class="file" name="file" id="file" accept=".xml">
                        <span id="error-file" class="error"></span>
                        <button id="whereprints-btn" class="link-button-blue">Where do I find my blueprints?</button>
                    </div>
                </div>
                <div class="vert-line"></div>
                <div class="form-picture">
                    <label for="pic" class="h3">Blueprint picture</label>
                    <img src="https://placehold.co/300" alt="preview" id="preview" width="300">
                    <input type="file" class="file" name="pic" id="pic" accept="image/png, image/jpeg">
                    <span id="error-pic" class="error"></span>
                </div>
            </div>
            <button type="submit" class="btn" id="submit">Upload</button>
        </form>
        <div class="col" id='loader'>
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