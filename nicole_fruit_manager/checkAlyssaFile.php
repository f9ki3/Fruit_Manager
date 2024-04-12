<?php
// Specify the path to your nicole_cervantes.xml file
$alyssaFilePath = 'nicole_cervantes.xml'; // Update with the correct path

// Check if the file exists
if (file_exists($alyssaFilePath)) {
    // Check if the file is readable
    if (is_readable($alyssaFilePath)) {
        // Read the content of the file
        $content = file_get_contents($alyssaFilePath);

        // Trim any whitespace characters (like newlines) from the content
        $trimmedContent = trim($content);

        // Check if the trimmed content is empty
        if (empty($trimmedContent)) {
            // Output "empty" if the file is empty
            echo "The file exists but is empty.";
        } else {
            // Output "not empty" if the file has content
            echo "The file exists and contains data.";
        }
    } else {
        // Output an error message if the file is not readable
        echo "";
    }
} else {
    // Output an error message if the file doesn't exist
    echo "";
}
?>
