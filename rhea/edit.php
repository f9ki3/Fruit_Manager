<?php
// Load XML file
$xmlFile = 'database.xml';
$xml = simplexml_load_file($xmlFile);

// Function to save updated XML back to file
function saveXMLToFile($xml, $file) {
    $dom = new DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xml->asXML());
    $dom->save($file);
}

// Handle POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decode JSON payload
    $data = json_decode(file_get_contents("php://input"));

    if ($data->action === 'updateBasketOwner') {
        // Update basket owner
        $basketNo = (int) $data->basketNo;
        $newOwner = htmlspecialchars($data->newOwner);

        foreach ($xml->basket as $basket) {
            if ((int) $basket->basketNo === $basketNo) {
                $basket->basketOwner = $newOwner;
                break;
            }
        }

        // Save changes back to XML file
        saveXMLToFile($xml, $xmlFile);
        echo json_encode(['success' => true]);
    } elseif ($data->action === 'updateFruitQuantity') {
        // Update fruit quantity
        $basketNo = (int) $data->basketNo;
        $fruitName = htmlspecialchars($data->fruitName);
        $newQuantity = (int) $data->newQuantity;

        foreach ($xml->basket as $basket) {
            if ((int) $basket->basketNo === $basketNo) {
                // Update specific fruit quantity
                foreach ($basket->fruits->fruit as $fruit) {
                    if ((string) $fruit->name === $fruitName) {
                        $fruit->quantity = $newQuantity;
                        break;
                    }
                }
                break;
            }
        }

        // Save changes back to XML file
        saveXMLToFile($xml, $xmlFile);
        echo json_encode(['success' => true]);
    } elseif ($data->action === 'deleteBasket') {
        // Delete basket
        $basketNo = (int) $data->basketNo;

        foreach ($xml->basket as $index => $basket) {
            if ((int) $basket->basketNo === $basketNo) {
                // Remove the basket from XML
                unset($xml->basket[$index]);
                break;
            }
        }

        // Save changes back to XML file
        saveXMLToFile($xml, $xmlFile);
        echo json_encode(['success' => true]);
    }
}
?>
