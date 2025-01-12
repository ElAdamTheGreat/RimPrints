<?php
/**
 * This file is the getImagePath function. It is used to get the path of an image based on its ID. If the image is not found, it returns a placeholder image.
 * @author Adam Gombos
 */

/**
 * @param int $id The ID of the image.
 * @return string The path of the image.
 */
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