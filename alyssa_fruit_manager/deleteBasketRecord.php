<?php
// Check if recordId parameter is set in the GET request
if (isset($_GET['recordId'])) {
    $recordId = $_GET['recordId'];

    // Load the XML file
    $xmlFilePath = 'alyssa.xml';
    $xml = simplexml_load_file($xmlFilePath);

    if ($xml) {
        // Find the record with the given ID
        $recordToDelete = $xml->xpath("//basket_record[@id='$recordId']");

        // If record is found, delete it
        if ($recordToDelete) {
            unset($recordToDelete[0][0]);
            // Save the changes back to the XML file
            $xml->asXML($xmlFilePath);
            // Return success status
            echo 'Record deleted successfully.';
        } else {
            // Return error if record not found
            header('HTTP/1.1 404 Not Found');
            echo 'Record not found.';
        }
    } else {
        // Return error if unable to load XML
        header('HTTP/1.1 500 Internal Server Error');
        echo 'Error loading XML file.';
    }
} else {
    // Return error if recordId parameter is missing
    header('HTTP/1.1 400 Bad Request');
    echo 'Missing recordId parameter.';
}
?>
