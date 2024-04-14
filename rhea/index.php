<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruit Basket Manager</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <div class="header">
        <h1>Fruit Basket Manager</h1>
        <div style="display: flex; flex-direction: row; width: 100%; justify-content: space-between; align-items: center;">
            <h4 style="margin: 0;">Basket Owner List</h4>
            <div>
                <button id="reloadButton">Refresh</button>
                <button id="btnAddCategory">Add Fruit Category</button>
                <button id="btnAddBasket">Add Basket Owner</button>
            </div>
        </div>


        <div>
            
            <div style="display: none" id="addCategoryDiv">
            <hr>
                <h5>Add Category</h5>
                <div style="display: flex; flex-direction: row;">
                    <input id="category" type="text" style="margin-left: 10px;" placeholder="Category">
                    <button style="margin-left: 10px;" onclick="addCategory()">Add</button>
                    <button style="margin-left: 10px;" onclick="reset_category()">Reset</button>
                </div>
            </div>
            
            <div style="display: none" id="addOwnerDiv">
            <hr>
                <h5>Add Basket Owner</h5>
                <div id="categories" style="display: flex; flex-direction: row;">
                    <input id="ownerName" type="text" placeholder="Owner Name">
                    <div id="categoriesContainer">
                        <!-- Categories from XML will be displayed here -->
                        <!-- <input type="number" class="categoryInput" value="0">
                        <input type="number" class="categoryInput" value="0">
                        <input type="number" class="categoryInput" value="0"> -->
                    </div>
                    <input type="hidden" id="totalNumber" value="0">
                    <button style="margin-left: 10px;" onclick="addRecord()">Add</button>
                </div>
            </div>
        </div>
    </div>
    <hr>

    <div >
        <table id="basketTableContainer" style="text-align: center;">
            <?php include 'header.php'?>
        </table>
    </div>

    

    <script src="addCategory.js"></script>
    <script src="display_category.js"></script>
    <script src="addRecord.js"></script>
    <script>
        // Function to toggle visibility of addCategoryDiv
        function toggleAddCategoryDiv() {
            var addCategoryDiv = document.getElementById('addCategoryDiv');
            addCategoryDiv.style.display = (addCategoryDiv.style.display === 'none') ? 'block' : 'none';
        }

        // Function to toggle visibility of addOwnerDiv
        function toggleAddOwnerDiv() {
            var addOwnerDiv = document.getElementById('addOwnerDiv');
            addOwnerDiv.style.display = (addOwnerDiv.style.display === 'none') ? 'block' : 'none';
        }

        // Event listener for "Add Fruit Category" button
        document.getElementById('btnAddCategory').addEventListener('click', function() {
            toggleAddCategoryDiv(); // Toggle visibility of addCategoryDiv
            // Hide addOwnerDiv if it's visible
            document.getElementById('addOwnerDiv').style.display = 'none';
        });

        // Event listener for "Add Basket Owner" button
        document.getElementById('btnAddBasket').addEventListener('click', function() {
            toggleAddOwnerDiv(); // Toggle visibility of addOwnerDiv
            // Hide addCategoryDiv if it's visible
            document.getElementById('addCategoryDiv').style.display = 'none';
        });

        // Function to handle adding a new fruit category (placeholder function)
        function addCategory() {
            var categoryName = document.getElementById('categoryName').value;
            // Add your logic here to save the category (e.g., AJAX request)
            console.log('Adding category:', categoryName);
            // Optionally, hide the addCategoryDiv after adding the category
            document.getElementById('addCategoryDiv').style.display = 'none';
        }

        // Function to handle adding a new basket owner (placeholder function)
        function addOwner() {
            var ownerName = document.getElementById('ownerName').value;
            // Add your logic here to save the owner (e.g., AJAX request)
            console.log('Adding owner:', ownerName);
            // Optionally, hide the addOwnerDiv after adding the owner
            document.getElementById('addOwnerDiv').style.display = 'none';
        }
        // JavaScript code for handling the button click event
        function addCategory() {
            var categoryInput = document.getElementById('category');
            var category = categoryInput.value.trim(); // Get the trimmed value from the input field

            // Check if category input is empty
            if (category === '') {
                alert('Please enter a category.');
                return; // Exit function early if input is empty
            }

            // Make an AJAX request to send the category value to the PHP script
            $.ajax({
                type: 'POST', // Use POST method to send data
                url: 'addCategory.php', // PHP script URL where data will be sent
                data: { category: category }, // Data to be sent
                success: function(response) {
                    // Handle the response from the server (if needed)
                    console.log(response); // Log the response to the console
                    
                    // Reload the page after a successful response
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    // Handle errors (if any)
                    console.error(error); // Log the error to the console
                }
            });

            // Clear input field after successful submission (optional)
            categoryInput.value = '';
        }

        // Add a click event listener to the button
        document.getElementById('reloadButton').addEventListener('click', function() {
            // Reload the current page when the button is clicked
            location.reload();
        });
        

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
            }
        };

        // Set up a function to handle network errors
        xhr.onerror = function() {
            console.error('Network error occurred while trying to delete file.');
        };

        // Send the AJAX request
        xhr.send();
    }






    </script>
</body>
</html>

