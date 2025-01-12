<?php
/**
 * This file is the admin page. It is used to display the user administration page for the admin user.
 * It handles various AJAX requests for user management such as fetching all users, deleting a user, and changing a user's role.
 * 
 * @author Adam Gombos
 */

session_start();

/**
 * Check if the user is signed in and is an admin
 */
if (!($_SESSION['isSignedIn'] ?? false) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

include('server/queries.php');
include('components/loader/loader.php');

/**
 * Handle AJAX request to fetch all users
 */
if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    $users = getAllUsers();

    if (!$users) {
        echo json_encode(['error' => '204']);
        exit;
    }

    $response = [];
    foreach ($users as $user) {
        $response[] = [
            'userId' => $user->id,
            'username' => htmlspecialchars($user->username),
            'email' => htmlspecialchars($user->email),
            'role' => $user->role,
            'prints' => $user->prints,
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

/**
 * Handle AJAX request to delete a user
 */
if (isset($_GET['ajax']) && $_GET['ajax'] == 2) {
    $userId = $_POST['userId'];

    $result = deleteUser($userId);
    if ($result === true) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

/**
 * Handle AJAX request to change a user's role
 */
if (isset($_GET['ajax']) && $_GET['ajax'] == 3) {
    $userId = $_POST['userId'];
    $role = $_POST['role'];

    $result = changeUserRole($userId, $role);
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
    <link rel="shortcut icon" type="image/x-icon" href="lib/favicon.ico">
    <link rel="stylesheet" href="styles/universal.css">
    <link rel="stylesheet" href="styles/admin.css">
    <link rel="stylesheet" href="components/loader/loader.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
    <script type="module" src="js/admin.js"></script>
    <script type="module" src="js/universal.js"></script>
    <title>User administration - RimPrints</title>
</head>
<body>
<nav class="nav">
    <a href="index.php" class="nav-title">R i m P r i n t s</a>
    <a href="index.php" class="nav-title-mobile">R</a>
        <div class="nav-links">
            <?php if ($_SESSION['role'] === 'admin') { ?>
                <a href="admin.php">Administration</a>
            <?php } ?>
            <?php if (($_SESSION['isSignedIn'] ?? false) === true ) { ?>
                <button class="link-button" id="signout-btn">Sign out</button>
            <?php } else { ?>
                <a href="signin.php">Sign in</a>
            <?php } ?>
        </div>
    </nav>
    <div id="content" class="content">
        <div class="col center" id="loading">
            <?php loader() ?>
            <h3 class="low-key">Loading data...</h3>
        </div>
        <div class="section" id="section">
            <h1 class="section-title">User administration</h1>
            <table id="table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Prints</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="user-table">
                </tbody>
            </table>
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
    <div class="modal" id="modal-userDelete">
        <div class="modal-content">
            <h2>Delete user?</h2>
            <p>Are you sure you want to delete this user? This action is irreversible.</p>
            <div class="modal-buttons" id="delete-buttons">
                <button class="btn" id="modal-userDelete-close">Cancel</button>
                <button class="btn-red" id="modal-userDelete-confirm">Delete</button>
            </div>
        </div>
    </div>
    <div class="modal" id="modal-userPromote">
        <div class="modal-content">
            <h2>Promote user?</h2>
            <p>Are you sure you want to promote this user?</p>
            <div class="modal-buttons" id="promote-buttons">
                <button class="btn" id="modal-userPromote-close">Cancel</button>
                <button class="btn-red" id="modal-userPromote-confirm">Promote</button>
            </div>
        </div>
    </div>
    <div class="modal" id="modal-userDemote">
        <div class="modal-content">
            <h2>Demote user?</h2>
            <p>Are you sure you want to demote this user?</p>
            <div class="modal-buttons" id="demote-buttons">
                <button class="btn" id="modal-userDemote-close">Cancel</button>
                <button class="btn-red" id="modal-userDemote-confirm">Demote</button>
            </div>
        </div>
    </div>
</body>
</html>