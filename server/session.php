<?php
/**
 * This file is the session file. It is used to check if a user is signed in. If they are, it returns true, otherwise it returns false.
 * @author Adam Gombos
 */

session_start();
echo json_encode(['isSignedIn' => $_SESSION['isSignedIn'] ?? false]);
?>
