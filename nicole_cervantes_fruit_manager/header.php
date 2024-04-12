<?php
function displayBasketRecords() {
    $xmlFilePath = 'categories.xml';

    // Check if the XML file exists
    if (file_exists($xmlFilePath)) {
        // Load the XML file
        $xml = simplexml_load_file($xmlFilePath);

        // Start building the HTML content for the table
        $html = '<div id="basketTableContainer" style="text-align: center;">
                    <table border="1">
                        <thead style="background-color: ;">
                            <tr>
                                <th style="text-align: center; width: 15%">Record ID</th>
                                <th style="text-align: center; width: 20%">Owner Name</th>';

        // Collect all unique categories from categories.xml
        $categories = [];
        foreach ($xml->category as $category) {
            $categoryValue = (string)$category;
            if (!in_array($categoryValue, $categories)) {
                $categories[] = $categoryValue;
                $html .= '<th style="text-align: center; width: 10%">' . htmlspecialchars($categoryValue) . '</th>';
            }
        }

        // Complete the rest of the thead
        $html .= '<th style="text-align: center;">Total Number</th>
                    <th style="text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>';

        // Call the function to get the tbody content
        $tbodyContent = getBasketRecordsTBody();

        // Append the tbody content
        $html .= $tbodyContent;

        // Close the table and container div
        $html .= '</tbody>
                </table>
            </div>';

        // Output the table HTML
        echo $html;

        // Output JavaScript for handling delete button clicks
        echo '<script>
        function deleteRecord(recordId) {
            if (confirm("Are you sure you want to delete this record?")) {
                // Send an AJAX request to deleteBasketRecord.php
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4) {
                        if (this.status == 200) {
                            // Reload the page to reflect changes after deletion
                            location.reload();
                        } else {
                            // Display error message if deletion fails
                            alert("Error: " + this.responseText);
                        }
                    }
                };
                xhttp.open("GET", "deleteBasketRecord.php?recordId=" + recordId, true);
                xhttp.send();
            }
        }
        </script>';

    } else {
        // Handle case where categories.xml does not exist
        // echo 'Categories XML file not found.';
    }
}

function getBasketRecordsTBody() {
    $xmlFilePath = 'nicole_cervantes.xml';

    // Check if the XML file exists
    if (file_exists($xmlFilePath)) {
        // Load the XML file
        $xml = simplexml_load_file($xmlFilePath);

        // Start building the HTML content for the tbody
        $tbody = '';

        $maxTotalNumber = 0; // Initialize max totalNumber

        // Loop through each basket_record element to find maximum totalNumber
        foreach ($xml->basket_record as $record) {
            $totalNumber = (int)$record['totalNumber'];
            if ($totalNumber > $maxTotalNumber) {
                $maxTotalNumber = $totalNumber;
            }
        }

        // Loop through each basket_record element to generate tbody content
        foreach ($xml->basket_record as $record) {
            $recordId = (string)$record['id'];
            $ownerName = (string)$record['ownerName'];
            $totalNumber = (int)$record['totalNumber'];
           

            // Determine row color based on totalNumber
            if ($totalNumber == $maxTotalNumber) {
                $rowColor = 'yellow'; // Highest totalNumber
            } elseif ($totalNumber > 5 && $totalNumber < $maxTotalNumber) {
                $rowColor = 'lightblue'; // Between 5 and highest totalNumber
            } else {
                $rowColor = 'salmon'; // Default color for other cases
            }


            // Build row with specified color
            $tbody .= "<tr style='background-color: $rowColor; border-bottom: 1px solid black'>
                            <td style='text-align: center;' id='recordId'>$recordId</td>
                            <td style='text-align: center;'><input id='ownerName' style='background: transparent; border: none; text-align: center; height: 30px' value='$ownerName'></td>";

            // Loop through categories for this record
            foreach ($record->category as $cat) {
                $categoryValue = (string)$cat['value'];
                $tbody .= '<td style="text-align: center;"><input id="categoryQTY" style="background: transparent; border: none; text-align: center; height: 30px"  value="' . htmlspecialchars($categoryValue) . '"></td>';
            }

            // Add total number and action button with onclick event
            $tbody .= "<td style='text-align: center;'>$totalNumber</td>
                            <td style='text-align: center;'>
                                <button style='margin-left: 10px; background-color: rgb(43, 3, 95); color: white; border: none; border-radius: 10px; width: 50%; padding: 10px; '  onclick='deleteRecord(\"$recordId\")'>Delete</button>
                            </td>
                        </tr>";
        }

        // Return the HTML content for the tbody
        return $tbody;
    } else {
        // Return an empty string if the XML file does not exist
        return '<tr><td colspan="6" style="text-align: center;">No basket records found.</td></tr>';
    }
}

// Call the function to display basket records
displayBasketRecords();

?>
