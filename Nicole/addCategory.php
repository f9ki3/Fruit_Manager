<?php
// Check if category data is received via POST
if(isset($_POST['category'])) {
    $category = $_POST['category'];

    $xmlFilePath = 'categories.xml';

    // Check if database.xml file exists
    if (!file_exists($xmlFilePath)) {
        // Create a new XML structure
        $xml = new SimpleXMLElement('<categories></categories>');
    } else {
        // Load existing XML file
        $xml = simplexml_load_file($xmlFilePath);
    }

    // Append new category as a child element to the XML
    $newCategory = $xml->addChild('category', htmlspecialchars($category));

    // Save the updated XML back to the file
    $xml->asXML($xmlFilePath);

    // Respond back to the client-side JavaScript
    echo 'Category added successfully!';
} else {
    // Handle if category data is not received
    echo 'Error: Category not provided.';
}
?>

<?php
// Check if category data is received via POST
if(isset($_POST['category'])) {
    $categoryValue = $_POST['category'];

    $xmlFilePath = 'basket_record.xml';

    // Check if basket_record.xml file exists
    if (!file_exists($xmlFilePath)) {
        // Create a new XML structure for basket_record
        $basketXml = new SimpleXMLElement('<basket_record ownerName="Fyke" totalNumber="19"></basket_record>');
    } else {
        // Load existing XML file for basket_record
        $basketXml = simplexml_load_file($xmlFilePath);
    }

    // Append new category as a child element to the basket_record XML
    $newCategory = $basketXml->addChild('category');
    $newCategory->addAttribute('id', ''); // Set id attribute to empty string
    $newCategory->addAttribute('value', '0'); // Set value attribute to 0

    // Save the updated XML back to the file
    $basketXml->asXML($xmlFilePath);

    // Respond back to the client-side JavaScript
    echo 'Category added successfully to basket_record!';
} else {
    // Handle if category data is not received
    echo 'Error: Category not provided.';
}
?>
