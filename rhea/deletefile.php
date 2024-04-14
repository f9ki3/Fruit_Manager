<?php
// List of files to delete
$filesToDelete = array('rhea.xml', 'categories.xml');

// Function to delete a file
function deleteFile($filename) {
    if (file_exists($filename)) {
        if (unlink($filename)) {
            echo "File '$filename' deleted successfully.";
        } else {
            echo "Failed to delete file '$filename'.";
        }
    } else {
        echo "File '$filename' does not exist.";
    }
}

// Loop through each file in the list and delete it
foreach ($filesToDelete as $file) {
    deleteFile($file);
}
?>
