<?php
session_start();
echo json_encode(['isSignedIn' => $_SESSION['isSignedIn'] ?? false]);
?>
