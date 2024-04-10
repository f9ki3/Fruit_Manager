<?php
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Load basket data from XML and return as JSON response
if ($action === 'get_data') {
    $xml = simplexml_load_file('database.xml');
    $data = [];

    foreach ($xml->basket as $basket) {
        $basketData = [
            'basketNo' => (int)$basket->basketNo,
            'basketOwner' => (string)$basket->basketOwner,
            'fruits' => []
        ];

        foreach ($basket->fruits->children() as $fruit) {
            $basketData['fruits'][(string)$fruit->name] = (int)$fruit->quantity;
        }

        $data[] = $basketData;
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

if ($action === 'delete_basket') {
    $basketNo = isset($_POST['basketNo']) ? (int)$_POST['basketNo'] : null;

    if ($basketNo !== null) {
        $xml = simplexml_load_file('database.xml');

        // Find the basket with the matching basketNo
        $basketToDelete = null;
        foreach ($xml->basket as $basket) {
            if ((int)$basket->basketNo === $basketNo) {
                $basketToDelete = $basket;
                break;
            }
        }

        if ($basketToDelete !== null) {
            // Remove the basket node
            $dom = dom_import_simplexml($basketToDelete);
            $dom->parentNode->removeChild($dom);

            // Save the updated XML back to the file
            $xml->asXML('database.xml');

            echo json_encode(['success' => true, 'message' => 'Basket deleted successfully']);
        } else {
            // Basket with given basketNo not found
            echo json_encode(['success' => false, 'message' => 'Basket not found']);
        }
    } else {
        // If basketNo parameter is missing or invalid
        echo json_encode(['success' => false, 'message' => 'Invalid or missing basketNo parameter']);
    }
    exit; // Terminate script execution after handling the request
}

// Insert a new basket into the XML file
if ($action === 'insert_basket') {
    // Retrieve POST data sent from frontend
    $ownerName = isset($_POST['ownerName']) ? $_POST['ownerName'] : '';
    $fruits = isset($_POST['fruits']) ? $_POST['fruits'] : [];

    // Validate input
    if (empty($ownerName) || !is_array($fruits) || count($fruits) === 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
        exit;
    }

    // Load XML data
    $xml = simplexml_load_file('database.xml');

    // Create a new <basket> node
    $newBasket = $xml->addChild('basket');
    $newBasket->addChild('basketNo', getNextBasketNo($xml)); // Generate next available basketNo
    $newBasket->addChild('basketOwner', htmlspecialchars($ownerName)); // Sanitize ownerName

    // Add fruits to the basket
    $fruitsNode = $newBasket->addChild('fruits');
    foreach ($fruits as $fruitName) {
        $fruitNode = $fruitsNode->addChild('fruit');
        $fruitNode->addChild('name', htmlspecialchars($fruitName)); // Sanitize fruitName
        $fruitNode->addChild('quantity', 1); // Default quantity
    }

    // Save the updated XML back to the file
    $xml->asXML('database.xml');

    echo json_encode(['success' => true, 'message' => 'Basket added successfully']);
    exit;
}

// Handle other actions or invalid requests
echo json_encode(['success' => false, 'message' => 'Invalid action']);
exit;

// Helper function to get the next available basketNo
function getNextBasketNo($xml) {
    // Find the maximum basketNo currently in use and generate the next available one
    $maxBasketNo = 0;
    foreach ($xml->basket as $basket) {
        $basketNo = (int)$basket->basketNo;
        if ($basketNo > $maxBasketNo) {
            $maxBasketNo = $basketNo;
        }
    }
    return $maxBasketNo + 1; // Return next available basketNo
}


// Handle other actions or invalid requests
echo json_encode(['success' => false, 'message' => 'Invalid action']);
exit;
?>
