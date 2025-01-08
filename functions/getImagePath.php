<?php
function getImagePath($id) {
    $directory = __DIR__ . '/../lib/img/';
    $extensions = ['jpg', 'png', 'jpeg'];

    foreach ($extensions as $ext) {
        $filePath = $directory . $id . '.' . $ext;
        error_log("Checking file: " . $filePath); // Debugging line
        if (file_exists($filePath)) {
            error_log("File exists: " . $filePath); // Debugging line
            return 'lib/img/' . $id . '.' . $ext;
        }
    }
    error_log("File not found, returning placeholder"); // Debugging line
    return 'lib/img/placeholder.png';
}