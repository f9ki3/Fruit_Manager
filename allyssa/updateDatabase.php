<?php
// Receive POST data
$recordId = $_POST['recordId'];
$ownerName = $_POST['ownerName'];
$categoryValues = $_POST['categoryValues'];

// Load XML file
$xml = simplexml_load_file('allyssa.xml');

// Find the specific basket_record by recordId
$recordToUpdate = $xml->xpath("//basket_record[@id='$recordId']")[0];
if ($recordToUpdate) {
    // Update ownerName
    $recordToUpdate['ownerName'] = $ownerName;

    // Update category values and calculate new totalNumber
    $totalNumber = 0;
    $i = 0;
    foreach ($recordToUpdate->category as $category) {
        $category['value'] = $categoryValues[$i];
        $totalNumber += (int)$categoryValues[$i]; // Summing up category values
        $i++;
    }

    // Update totalNumber in the basket_record
    $recordToUpdate['totalNumber'] = $totalNumber;

    // Save updated XML back to file
    $xml->asXML('allyssa.xml');

    echo 'XML updated successfully';
} else {
    echo 'Record not found';
}
?> 
