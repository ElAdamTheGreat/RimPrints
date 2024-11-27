<?php
$isSignedIn = false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="lib/favicon.ico" />
    <link rel="stylesheet" href="styles/universal.css">
    <link rel="stylesheet" href="styles/upload.css">
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
    <div class="content">
        <h1>Upload Blueprint</h1>
        <form action="upload.php" class="form" method="post" enctype="multipart/form-data">
            <div class="form-data">
                <div class="form-sec">
                    <label for="title" class="h3">Blueprint title</label>
                    <input type="text" class="form-input" id="title" name="title" required>
                </div>
                <input type="file" name="file" id="file" accept=".xml" required>
                <input type="submit" class="btn-sm" value="Upload">
            </div>
            <div class="vert-line"></div>
            <div class="form-picture">
                <img src="https://placehold.co/300" alt="placeholder">
            </div>
        </form>
    </div>
</body>
</html>