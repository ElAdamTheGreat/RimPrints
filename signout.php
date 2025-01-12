<?php
/**
 * This file handles the sign-out process.
 * It clears the user session and redirects to the previous page or the main page.
 * 
 * @author Adam Gombos
 */

session_start();

if (isset($_SESSION['isSignedIn'])) {
    unset($_SESSION['isSignedIn'], $_SESSION['userId'], $_SESSION['username'], $_SESSION['email'], $_SESSION['role'], $_SESSION['prints']);
    $redirectUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
    header('Location: ' . $redirectUrl);
    exit;
}
?>