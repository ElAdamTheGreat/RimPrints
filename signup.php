<?php
session_start();
// is user not signed in?
if (($_SESSION['isSignedIn'] ?? false)) {
    header('Location: index.php');
    exit;
}

include('server/queries.php');

if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    $username = $_POST['username'] ?? '';

    if (strlen($username) < 4 || strlen($username) > 16) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid data']);
        exit;
    }

    $result = isUsernameTaken($username);
    
    if ($result) {
        header('Content-Type: application/json');
        echo json_encode(['taken' => true]);
        exit;
    } else {
        header('Content-Type: application/json');
        echo json_encode(['taken' => false]);
        exit;
    }
}

if (isset($_GET['ajax']) && $_GET['ajax'] == 2) {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // validate data (server side)
    if (strlen($username) < 4 || strlen($username) > 16 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 4) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid data']);
        exit;
    }

    $hashedPass = password_hash($password, PASSWORD_DEFAULT);
    $userId = createUser($username, $email, $hashedPass, 'user');
    
    // fill session data
    if ($userId > 0) {
        $_SESSION['isSignedIn'] = true;
        $_SESSION['userId'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = 'user';
        $_SESSION['prints'] = 0;
    }

    // send data back to client
    header('Content-Type: application/json');
    echo json_encode(['id' => $userId]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="lib/favicon.ico" />
    <link rel="stylesheet" href="styles/universal.css">
    <link rel="stylesheet" href="styles/login.css">
    <link rel="stylesheet" href="components/loader/loader.css">
    <script type="module" src="js/signup.js" defer></script>
    <script type="module" src="js/universal.js" defer></script>
    <title>Sign up - RimPrints</title>
</head>
<body>
<nav class="nav">
        <a href="index.php" class="nav-title"><h1>R i m P r i n t s</h1></a>
        <div class="nav-links">
            <a href="signin.php">Sign in</a>
        </div>
    </nav>
    <div class="content">
        <form method="POST" id="registration-form">
            <h3>Sign up to RimPrints</h3>
            <div class="form-sec">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-input" autocomplete="one-time-code">
                <span id="error-username" class="error"></span>
                <span id="info-username" class="info"></span>
            </div>
            <div class="form-sec">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-input" autocomplete="one-time-code">
                <span id="error-email" class="error"></span>
            </div>
            <div class="form-sec">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input" autocomplete="new-password">
                <span id="error-password" class="error"></span>
            </div>
            
            <span id="error" class="error text-center"></span>

            <button type="submit" id="submit" class="btn" disabled>Sign up</button>

            <a href="signin.php" class="blue-link">Already have an account? Sign in</a>
        </form>
    </div>
</body>
</html>