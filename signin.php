<?php
session_start();

include('server/queries.php');
include('components/loader/loader.php');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['ajax'])) {
    $ajaxType = $_GET['ajax'];
    $usermail = $_POST['usermail'] ?? '';

    $email = null;
    $username = null;

    if ($ajaxType == 1) {
        $password = $_POST['password'] ?? '';

        if (strlen($password) < 4) { // === max character limit in db?
            echo json_encode(['success' => false, 'error' => 'Please enter a password with valid length.']);
            exit;
        }

        // in case of login, it must be recognised if user typed email or username
        if (strpos($usermail, '@') !== false) {
            $email = $usermail;
            // serverside validation
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'error' => 'Please enter a valid email address.']);
                exit;
            }
            $hashedPass = checkUserPass(email: $email);
        } else {
            $username = $usermail;

            if (strlen($username) < 4 || strlen($username) > 16) {
                echo json_encode(['success' => false, 'error' => 'Username length must be between 4 and 16 characters.']);
                exit;
            }
            $hashedPass = checkUserPass(username: $username);
        }
        if ($hashedPass === null) {
            echo json_encode(['success' => false, 'error' => 'User not found.']);
        } else if ($hashedPass && password_verify($password, $hashedPass)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Incorrect password.']);
        }
    } elseif ($ajaxType == 2) {

        // in case of login, it must be recognised if user typed email or username
        // Handle second AJAX request
        if (strpos($usermail, '@') !== false) {
            $email = $usermail;
            $userDetails = getUserByUsermail(email: $email);
        } else {
            $username = $usermail;
            $userDetails = getUserByUsermail(username: $username);
        }

        if ($userDetails) {
            // Extract user details
            $userId = $userDetails->id;
            $username = $userDetails->username;
            $email = $userDetails->email;
            $role = $userDetails->role;
            $prints = $userDetails->prints;

            // 1. get user details and fill them to session
            echo json_encode([
                'success' => true,
                'user' => [
                    'id' => $userId,
                    'username' => $username,
                    'email' => $email,
                    'role' => $role,
                    'prints' => $prints
                ]
            ]);

            $_SESSION['isSignedIn'] = true;
            $_SESSION['userId'] = $userId;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;
            $_SESSION['prints'] = $prints;
        } else {
            echo json_encode(['success' => false, 'error' => 'User not found.']);
        }
    }
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
    <script src="js/signin.js" defer></script>
    <title>Sign in - RimPrints</title>
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
    <div id="content" class="content">
        <form method="POST" id="registration-form">
            <h3>Sign in to RimPrints</h3>
            <div class="form-sec">
                <label for="usermail">Username or email address</label>
                <input type="text" id="usermail" name="usermail" class="form-input" autocomplete="username">
            </div>
            <div class="form-sec">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input" autocomplete="current-password">
            </div>
            <span id="error" class="error"></span>
            <div id="status" class="status">
                <button type="submit" id="submit" class="btn-sm">Sign In</button>
            </div>
            <a href="signup.php" class="link">Don't have an account yet? Sign up</a>
        </form>
    </div>
</body>
</html>