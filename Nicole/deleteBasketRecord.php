<?php
// Check if recordId parameter is set in the GET request
if (isset($_GET['recordId'])) {
    $recordId = $_GET['recordId'];

    // Path to the XML file
    $xmlFilePath = 'basket_record.xml';

    // Check if the XML file exists
    if (file_exists($xmlFilePath)) {
        // Load the XML file
        $xml = simplexml_load_file($xmlFilePath);

        // Find the record with matching recordId and remove it
        $recordIndex = findRecordIndex($xml, $recordId);
        if ($recordIndex !== false) {
            unset($xml->basket_record[$recordIndex]);

            // Save the modified XML back to the file
            $xml->asXML($xmlFilePath);

            // Send a success response
            http_response_code(200); // Success HTTP status code
            echo json_encode(array('message' => 'Record deleted successfully.'));
            exit;
        }
    }

    // If recordId is not found or XML file cannot be loaded
    http_response_code(404); // Not Found HTTP status code
    echo json_encode(array('message' => 'Record not found or XML file could not be loaded.'));
    exit;
}

// Helper function to find the index of a record with a specific recordId
function findRecordIndex($xml, $recordId) {
    foreach ($xml->basket_record as $index => $record) {
        if ((string)$record['id'] === $recordId) {
            return $index; // Return the index of the matching record
        }
    }
    return false; // Return false if recordId is not found
}
?>
