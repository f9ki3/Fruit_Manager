<?php
$action = isset($_GET['action']) ? $_GET['action'] : '';

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
}

if ($action === 'add_basket') {
    $basketNo = $_POST['basketNo'];
    $basketOwner = $_POST['basketOwner'];

    $xml = simplexml_load_file('database.xml');

    // Check if basket with same number already exists
    if ($xml->xpath("//basket[basketNo='$basketNo']")) {
        echo json_encode(['success' => false, 'message' => 'Basket number already exists']);
        exit;
    }

    $basketNode = $xml->addChild('basket');
    $basketNode->addChild('basketNo', $basketNo);
    $basketNode->addChild('basketOwner', $basketOwner);
    $basketNode->addChild('fruits');

    $xml->asXML('database.xml');

    echo json_encode(['success' => true, 'message' => 'Basket added successfully']);
}

if ($action === 'update_fruit_quantity') {
    $basketNo = $_POST['basketNo'];
    $fruitName = $_POST['fruitName'];
    $newQuantity = $_POST['newQuantity'];

    $xml = simplexml_load_file('database.xml');

    $basket = $xml->xpath("//basket[basketNo='$basketNo']")[0];

    foreach ($basket->fruits->fruit as $fruit) {
        if ((string)$fruit->name === $fruitName) {
            $fruit->quantity = $newQuantity;
            break;
        }
    }

    $xml->asXML('database.xml');

    echo json_encode(['success' => true, 'message' => 'Fruit quantity updated successfully']);
}

if ($action === 'delete_basket') {
    $basketNo = $_POST['basketNo'];

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
    } else {
        echo json_encode(['success' => false, 'message' => 'Basket not found']);
    }
}
?>
