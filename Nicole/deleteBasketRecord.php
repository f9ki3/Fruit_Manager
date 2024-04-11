<?php
// Check if recordId is provided in the query string
if (isset($_GET['recordId'])) {
    // Retrieve the recordId from the query string
    $recordId = $_GET['recordId'];

    // Path to the basket_record XML file
    $xmlFilePath = 'basket_record.xml';

    // Load the XML file
    $xml = simplexml_load_file($xmlFilePath);

    // Find the basket_record node with matching id attribute
    $recordToDelete = null;
    foreach ($xml->basket_record as $record) {
        if ((string)$record['id'] === $recordId) {
            $recordToDelete = $record;
            break;
        }
    }

    if ($recordToDelete !== null) {
        // Remove the record node from XML
        unset($recordToDelete[0]);

        // Save the updated XML back to the file
        file_put_contents($xmlFilePath, $xml->asXML());

        // Return a success response
        echo json_encode(['success' => true, 'message' => 'Record deleted successfully']);
    } else {
        // Return an error response if recordId is not found
        echo json_encode(['success' => false, 'message' => 'Record not found']);
    }
} else {
    // Return an error response if recordId is not provided
    echo json_encode(['success' => false, 'message' => 'RecordId parameter is missing']);
}
?>
