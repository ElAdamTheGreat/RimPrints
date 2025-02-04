<?php
/**
 * This file handles the sign-in process.
 * It manages AJAX requests for user authentication and session management.
 * 
 * @author Adam Gombos
 */

session_start();
if (($_SESSION['isSignedIn'] ?? false)) {
    header('Location: index.php');
    exit;
}

include('server/queries.php');
include('components/loader/loader.php');

/**
 * Enable error reporting for debugging
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Handle AJAX requests
 */
if (isset($_GET['ajax'])) {
    $ajaxType = $_GET['ajax'];
    $usermail = $_POST['usermail'] ?? '';

    $email = null;
    $username = null;

    /**
     * Handle login request
     */
    if ($ajaxType == 1) {
        $password = $_POST['password'] ?? '';

        /**
         * Validate password length
         */
        if (strlen($password) < 4) {
            echo json_encode(['success' => false, 'error' => 'Please enter a password with valid length.']);
            exit;
        }

        /**
         * Determine if usermail is an email or username
         */
        if (strpos($usermail, '@') !== false) {
            $email = $usermail;
            /**
             * Server-side validation for email
             */
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
        /**
         * Handle second AJAX request
         */
        if (strpos($usermail, '@') !== false) {
            $email = $usermail;
            $userDetails = getUserByUsermail(email: $email);
        } else {
            $username = $usermail;
            $userDetails = getUserByUsermail(username: $username);
        }

        if ($userDetails) {
            /**
             * Extract user details
             */
            $userId = $userDetails->id;
            $username = $userDetails->username;
            $email = $userDetails->email;
            $role = $userDetails->role;
            $prints = $userDetails->prints;

            /**
             * Fill user details to session
             */
            $_SESSION['isSignedIn'] = true;
            $_SESSION['userId'] = $userId;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;
            $_SESSION['prints'] = $prints;

            /**
             * Send data back to client
             */
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
    <link rel="shortcut icon" type="image/x-icon" href="lib/favicon.ico">
    <link rel="stylesheet" href="styles/universal.css">
    <link rel="stylesheet" href="styles/login.css">
    <link rel="stylesheet" href="components/loader/loader.css">
    <script src="js/signin.js"></script>
    <script type="module" src="js/universal.js"></script>
    <title>Sign in - RimPrints</title>
</head>
<body>
    <nav class="nav">
        <a href="index.php" class="nav-title">R i m P r i n t s</a>
        <a href="index.php" class="nav-title-mobile">R</a>
        <div class="nav-links">
            <a href="signin.php">Sign in</a>
        </div>
    </nav>
    <div id="content" class="content">
        <form method="POST" id="registration-form">
            <h3>Sign in to RimPrints</h3>
            <div class="form-sec">
                <label for="usermail">Username or email address<span class="required">*</span></label>
                <input type="text" id="usermail" name="usermail" class="form-input" autocomplete="username">
                <span id="error-usermail" class="error"></span>
            </div>
            <div class="form-sec">
                <label for="password">Password<span class="required">*</span></label>
                <input type="password" id="password" name="password" class="form-input" autocomplete="current-password">
                <span id="error-password" class="error"></span>
            </div>
            <span id="error" class="error text-center"></span>

            <button type="submit" id="submit" class="btn">Sign In</button>

            <a href="signup.php" class="blue-link">Don't have an account yet? Sign up</a>
        </form>
    </div>
</body>
</html>