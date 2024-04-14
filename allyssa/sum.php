<?php
// Load the XML file
$xmlFile = 'allyssa.xml';
$xml = simplexml_load_file($xmlFile);

// Check if loading the XML file was successful
if ($xml === false) {
    die('Error loading XML file');
}

// Array to store sums of category values based on category ID
$categorySums = [];

// Loop through each <basket_record> element
foreach ($xml->basket_record as $basket) {
    // Loop through each <category> within the <basket_record>
    foreach ($basket->category as $category) {
        $categoryId = (int) $category['id'];
        $categoryValue = (int) $category['value'];

        // Add category value to the sum for this category ID
        if (isset($categorySums[$categoryId])) {
            $categorySums[$categoryId] += $categoryValue;
        } else {
            $categorySums[$categoryId] = $categoryValue;
        }
    }
}

// Output the sums of category values
echo "<h2>Sum of Category Values</h2>";

echo "<table border='1'>";
echo "<tr><th>Category ID</th><th>Sum of Values</th></tr>";

foreach ($categorySums as $categoryId => $sum) {
    echo "<tr><td>$categoryId</td><td>$sum</td></tr>";
}

echo "</table>";
?>
