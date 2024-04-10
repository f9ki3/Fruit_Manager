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

// Update basket owner in XML based on basketNo and newOwner
if ($action === 'update_basket_owner') {
    $basketNo = isset($_POST['basketNo']) ? $_POST['basketNo'] : null;
    $newOwner = isset($_POST['newOwner']) ? $_POST['newOwner'] : null;

    if ($basketNo !== null && $newOwner !== null) {
        $xml = simplexml_load_file('database.xml');
        $basket = $xml->xpath("//basket[basketNo='$basketNo']")[0];

        if ($basket) {
            $basket->basketOwner = $newOwner;
            $xml->asXML('database.xml');
            echo json_encode(['success' => true, 'message' => 'Basket owner updated successfully']);
            exit;
        }
    }

    echo json_encode(['success' => false, 'message' => 'Invalid request parameters']);
    exit;
}

// Update fruit quantity in XML based on basketNo, fruitName, and newQuantity
if ($action === 'update_fruit_quantity') {
    $basketNo = isset($_POST['basketNo']) ? $_POST['basketNo'] : null;
    $fruitName = isset($_POST['fruitName']) ? $_POST['fruitName'] : null;
    $newQuantity = isset($_POST['newQuantity']) ? $_POST['newQuantity'] : null;

    if ($basketNo !== null && $fruitName !== null && $newQuantity !== null) {
        $xml = simplexml_load_file('database.xml');
        $basket = $xml->xpath("//basket[basketNo='$basketNo']")[0];

        foreach ($basket->fruits->fruit as $fruit) {
            if ((string)$fruit->name === $fruitName) {
                $fruit->quantity = $newQuantity;
                $xml->asXML('database.xml');
                echo json_encode(['success' => true, 'message' => 'Fruit quantity updated successfully']);
                exit;
            }
        }
    }

    echo json_encode(['success' => false, 'message' => 'Invalid request parameters']);
    exit;
}

// Delete basket from XML based on basketNo
if ($action === 'delete_basket') {
    $basketNo = isset($_POST['basketNo']) ? $_POST['basketNo'] : null;

    if ($basketNo !== null) {
        $xml = simplexml_load_file('database.xml');
        $basketIndex = -1;

        foreach ($xml->basket as $index => $basket) {
            if ((int)$basket->basketNo === (int)$basketNo) {
                $basketIndex = $index;
                break;
            }
        }

        if ($basketIndex !== -1) {
            unset($xml->basket[$basketIndex]);
            $xml->asXML('database.xml');
            echo json_encode(['success' => true, 'message' => 'Basket deleted successfully']);
            exit;
        }
    }

    echo json_encode(['success' => false, 'message' => 'Invalid request parameters']);
    exit;
}

// Handle other actions or invalid requests
echo json_encode(['success' => false, 'message' => 'Invalid action']);
exit;
?>
