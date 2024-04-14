<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruit Basket Manager</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body >
    <div class="header">
        <h1 style="">Fruit Basket Manager</h1>
        <div>
        <div>
                <!-- <button style="margin-left: 10px;  border: 1px solid gray; color:  1px solid gray; border-radius: 10px; width: 10%; padding: 10px; " id="reloadButton" onclick="reFresh()">Refresh</button> -->
                <button style="margin-left: 10px;  border: 1px solid gray; color:  1px solid gray; border-radius: 10px; width: 10%; padding: 10px; " id="btnAddCategory">+ Add Fruit Category</button>
                <button style="margin-left: 10px;  border: 1px solid gray; color:  1px solid gray; border-radius: 10px; width: 10%; padding: 10px; " id="btnAddBasket">+ Add Basket Owner</button>
        </div>
        <hr>
        </div>

        
        <div style="display: flex;">
        
            <div style="width: 20%; display: none" id="div_category">
                <h5 style="font-size: 20px; margin: 5px 0px 10px 10px; font-weight: bolder; ">Add Category</h5>
                
                <div style="display: flex; flex-direction: row;">
                    <input id="category" type="text" style="margin-left: 10px; border-radius: 10px; border: 1px solid gray; padding-left: 10px" placeholder="Category">
                    <button style="margin-left: 10px;  border: 1px solid gray; color:  1px solid gray; border-radius: 10px; width: 100%; padding: 10px; " onclick="addCategory()">Add </button>
                    <button  style="margin-left: 10px;  border: 1px solid gray; color:  1px solid gray; border-radius: 10px; width: 100%; padding: 10px; " onclick="reset_category()">Reset </button>
                </div>
            </div >
            <div style="width: 80%; display: none" id="div_owner">
                <h5 style="font-size: 20px; margin: 5px 0px 10px 10px; font-weight: bolder; ">Add Basket Owner</h5>
                <div id="categories" style="display: flex; flex-direction: row;">
                    <input id="ownerName" style="margin-left: 10px; border-radius: 10px; border: 1px solid gray; padding-left: 10px" type="text" placeholder="Owner Name">
                    <div id="categoriesContainer">
                        <!-- Categories from XML will be displayed here -->
                        <!-- <input type="number" class="categoryInput" value="0">
                        <input type="number" class="categoryInput" value="0">
                        <input type="number" class="categoryInput" value="0"> -->
                    </div>
                    <input type="hidden" id="totalNumber" value="0">
                    <button style="margin-left: 10px;  border: 1px solid gray; color:  1px solid gray; border-radius: 10px; width: 10%; padding: 10px; " onclick="addRecord()">Add</button>
                </div>
            </div>
        </div>
    </div>

    <div >
        <table id="basketTableContainer" style="text-align: center;">
            <?php include 'header.php'?>
        </table>
    </div>

    

    <script src="addCategory.js"></script>
    <script src="display_category.js"></script>
    <script src="addRecord.js"></script>
    <script>
        // Function to update XML data when inputs are modified
        function updateXML(recordId, ownerName, categoryValues) {
            // Prepare data to be sent to server
            var data = {
                recordId: recordId,
                ownerName: ownerName,
                categoryValues: categoryValues
            };

            // Send AJAX request to PHP script
            $.ajax({
                type: 'POST',
                url: 'updateDatabase.php', // Path to your PHP script
                data: data,
                success: function(response) {
                    console.log('XML updated successfully');
                },
                error: function(xhr, status, error) {
                    console.error('Error updating XML:', error);
                }
            });
        }

    

        function reset_category() {
    // Create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Configure the request
    xhr.open('POST', 'deletefile.php', true);

    // Set the appropriate headers for a POST request
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    // Set up a function to handle the response
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            // Request was successful
            console.log('File deleted successfully.');
            // Reload the page upon successful deletion
            window.location.reload();
        } else {
            // Request failed
            console.error('Failed to delete file. Status code: ' + xhr.status);

            // Introduce an error to disrupt JSON or session
            try {
                // Attempt to parse JSON from response
                var responseJson = JSON.parse(xhr.responseText);
                console.log('Parsed JSON response:', responseJson);
            } catch (error) {
                // Log and ignore JSON parsing error
                console.error('JSON parsing error:', error.message);
            }

            // Trigger a fake session error
            sessionStorage.setItem('error', 'Session Error');
        }
    };

    // Set up a function to handle network errors
    xhr.onerror = function() {
        console.error('Network error occurred while trying to delete file.');
    };

    // Send the AJAX request
    xhr.send();
}


    function reFresh() {
        location.reload();
    }


    // Function to toggle visibility of div_category and div_owner
function toggleDivVisibility(divId) {
    var div = document.getElementById(divId);
    if (div.style.display === 'none') {
        div.style.display = 'block';
    } else {
        div.style.display = 'none';
    }
}

// Add event listeners to the buttons
document.addEventListener('DOMContentLoaded', function() {
    var btnAddCategory = document.getElementById('btnAddCategory');
    var btnAddBasket = document.getElementById('btnAddBasket');

    if (btnAddCategory && btnAddBasket) {
        btnAddCategory.addEventListener('click', function() {
            toggleDivVisibility('div_category');
            // Hide div_owner if it's currently visible
            var divOwner = document.getElementById('div_owner');
            if (divOwner.style.display !== 'none') {
                divOwner.style.display = 'none';
            }
        });

        btnAddBasket.addEventListener('click', function() {
            toggleDivVisibility('div_owner');
            // Hide div_category if it's currently visible
            var divCategory = document.getElementById('div_category');
            if (divCategory.style.display !== 'none') {
                divCategory.style.display = 'none';
            }
        });
    }
});

$(document).ready(function() {
    var editedRowData = {}; // To track edited row data

    // Listen for input changes within the table body
    $('tbody').on('input', 'input', function() {
        var $row = $(this).closest('tr');
        var recordId = $row.find('td:nth-child(1)').text();
        var ownerName = $row.find('input#ownerName').val();
        var categoryValues = [];
        $row.find('input:not(#ownerName)').each(function() {
            categoryValues.push($(this).val());
        });
        editedRowData = { recordId: recordId, ownerName: ownerName, categoryValues: categoryValues };
    });

    // Listen for Enter key press or click outside inputs
    $(document).on('keyup click', function(event) {
        if (event.type === 'keyup' && event.key !== 'Enter') {
            return; // Ignore keyup events unless it's Enter key
        }

        var $target = $(event.target);

        // Check if the event occurred outside of the tbody or input fields
        if (!$target.closest('tbody').length && !$target.is('input')) {
            // Perform update only if there's edited row data
            if (Object.keys(editedRowData).length > 0) {
                updateXML(editedRowData.recordId, editedRowData.ownerName, editedRowData.categoryValues);
                editedRowData = {}; // Clear edited data
                window.location.reload(); // Reload the page after update
            }
        }
    });
});



</script>

</body>
</html>

