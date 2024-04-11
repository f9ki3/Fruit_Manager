<?php
function displayBasketRecords() {
    $xmlFilePath = 'basket_record.xml';

    // Check if the XML file exists
    if (file_exists($xmlFilePath)) {
        // Load the XML file
        $xml = simplexml_load_file($xmlFilePath);

        // Start building the HTML content for the table
        $html = '<table border="1">
                    <thead>
                        <tr>
                            <th>Record ID</th>
                            <th>Owner Name</th>
                            <th>Categories</th>
                            <th>Total Number</th>
                        </tr>
                    </thead>
                    <tbody>';

        // Loop through each basket_record element
        foreach ($xml->basket_record as $record) {
            $recordId = (string)$record['id'];
            $ownerName = (string)$record['ownerName'];
            $totalNumber = (string)$record['totalNumber'];

            // Extract category values
            $categories = [];
            foreach ($record->category as $category) {
                $categories[] = (string)$category['value'];
            }
            $categoriesString = implode(', ', $categories);

            // Append row to HTML table
            $html .= "<tr>
                        <td><p style='text-align: center'> $recordId</p> </td>
                        <td><p style='text-align: center'> $ownerName</p> </td>
                        <td><p style='text-align: center'> $categoriesString</p> </td>
                        <td><p style='text-align: center'> $totalNumber</p> </td>
                      </tr>";
        }

        // Complete the HTML content
        $html .= '</tbody></table>';

        // Display the HTML content within the specified div
        echo '<div id="basketTableContainer">' . $html . '</div>';
    } else {
        // Display a message if the XML file does not exist
        echo '<div id="basketTableContainer">No basket records found.</div>';
    }
}

// Call the function to display basket records
displayBasketRecords();
?>
