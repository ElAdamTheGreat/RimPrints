<?php
session_start();

if (isset($_SESSION['isSignedIn'])) {
    unset($_SESSION['isSignedIn'], $_SESSION['userId'], $_SESSION['username'], $_SESSION['email'], $_SESSION['role'], $_SESSION['prints']);
    $redirectUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
    header('Location: ' . $redirectUrl);
    exit;
}