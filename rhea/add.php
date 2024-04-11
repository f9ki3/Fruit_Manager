<?php
// Define XML file path
$xmlFilePath = 'database.xml';

// Function to load XML file and return SimpleXMLElement
function loadXMLFile($filePath) {
    if (file_exists($filePath)) {
        return simplexml_load_file($filePath);
    } else {
        // If the XML file does not exist, create a new <baskets> element
        $xml = new SimpleXMLElement('<?xml version="1.0" ?><baskets></baskets>');
        $xml->asXML($filePath);
        return $xml;
    }
}

// Function to save SimpleXMLElement back to XML file
function saveXMLFile($xml, $filePath) {
    if ($xml instanceof SimpleXMLElement) {
        $xml->asXML($filePath);
        return true;
    } else {
        return false;
    }
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parse JSON payload
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate JSON data
    if (isset($data['ownerName']) && isset($data['fruits'])) {
        // Load XML file
        $xml = loadXMLFile($xmlFilePath);

        // Create new basket element
        $basket = $xml->addChild('basket');

        // Add basketNo
        $basketNo = $basket->addChild('basketNo', getNextBasketNo($xml));

        // Add basketOwner
        $basketOwner = $basket->addChild('basketOwner', htmlspecialchars($data['ownerName']));

        // Add fruits
        $fruits = $basket->addChild('fruits');
        $index = 1;
        foreach ($data['fruits'] as $fruitName => $quantity) {
            $fruit = $fruits->addChild('fruit');
            $fruit->addChild('name', 'Fruit ' . $index); // Set name as Fruit 1, Fruit 2, Fruit 3, ...
            $fruit->addChild('quantity', $quantity);
            $index++;
        }

        // Save updated XML file
        if (saveXMLFile($xml, $xmlFilePath)) {
            // Respond with success message
            echo json_encode(['success' => true, 'message' => 'Basket added successfully.']);
            exit;
        } else {
            // Respond with error message
            echo json_encode(['success' => false, 'message' => 'Failed to save XML file.']);
            exit;
        }
    } else {
        // Respond with error message
        echo json_encode(['success' => false, 'message' => 'Invalid request data.']);
        exit;
    }
} else {
    // Respond with error message for unsupported request method
    echo json_encode(['success' => false, 'message' => 'Unsupported request method.']);
    exit;
}

// Function to get the next available basketNo
function getNextBasketNo($xml) {
    // Find the maximum basketNo in the existing baskets
    $maxBasketNo = 0;
    foreach ($xml->basket as $basket) {
        $basketNo = (int)$basket->basketNo;
        if ($basketNo > $maxBasketNo) {
            $maxBasketNo = $basketNo;
        }
    }
    // Return the next available basketNo (incremented by 1)
    return $maxBasketNo + 1;
}
?>
