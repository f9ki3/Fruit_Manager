<?php
function getBasketRecordsTBody() {
    $xmlFilePath = 'basket_record.xml';

    // Check if the XML file exists
    if (file_exists($xmlFilePath)) {
        // Load the XML file
        $xml = simplexml_load_file($xmlFilePath);

        // Start building the HTML content for the tbody
        $tbody = '';

        // Loop through each basket_record element
        foreach ($xml->basket_record as $record) {
            $recordId = (string)$record['id'];
            $ownerName = (string)$record['ownerName'];
            $totalNumber = (string)$record['totalNumber'];

            // Convert empty totalNumber to 0
            $totalNumber = ($totalNumber === '') ? '0' : $totalNumber;

            // Start building row
            $tbody .= "<tr >
                        <td style='text-align: center;'>$recordId</td>
                        <td style='text-align: center;'>$ownerName</td>";

            // Loop through categories for this record
            foreach ($record->category as $cat) {
                $categoryValue = (string)$cat['value'];
                $tbody .= '<td style="text-align: center;">' . htmlspecialchars($categoryValue) . '</td>';
            }

            // Add total number
            $tbody .= "<td style='text-align: center;'>$totalNumber</td>
                        <td style='text-align: center;'><button onclick='deleteItems(\"$recordId\")'>Delete</button></td>
                    </tr>";
        }

        // Return the HTML content for the tbody
        return $tbody;
    } else {
        // Return an empty string if the XML file does not exist
        return '';
    }
}

// Usage: Call the function to get the tbody content
$tbodyContent = getBasketRecordsTBody();

// Display the tbody content within the specified div
if (!empty($tbodyContent)) {
    echo '<div id="basketTableContainer" style="text-align: center;">
            <table border="1">
                <tbody>
                    ' . $tbodyContent . '
                </tbody>
            </table>
          </div>';
} else {
    // Display a message if no basket records found
    echo '<div id="basketTableContainer" style="text-align: center;">No basket records found.</div>';
}
?>
