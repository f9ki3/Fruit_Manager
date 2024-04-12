<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruit Basket Manager</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body style="background-color: blueviolet;">
    <div class="header">
        <h1 style="color: white">Fruit Basket Manager</h1>
        <div>
        <hr>
        </div>
        <div style="display: flex;">
        
            <div style="width: 20%;">
                <h5 style="font-size: 20px; margin: 5px 0px 10px 10px; font-weight: bolder; color: white">Add Category</h5>
                <div style="display: flex; flex-direction: row;">
                    <input id="category" type="text" style="margin-left: 10px; border-radius: 10px; border: none; padding-left: 10px" placeholder="Category">
                    <button style="margin-left: 10px; background-color: rgb(43, 3, 95); color: white; border: none; border-radius: 10px; width: 100%; padding: 10px; " onclick="addCategory()">Add </button>
                </div>
            </div style="width: 80%">
            <div>
                <h5 style="font-size: 20px; margin: 5px 0px 10px 10px; font-weight: bolder; color: white">Add Basket Owner</h5>
                <div id="categories" style="display: flex; flex-direction: row;">
                    <input id="ownerName" style="margin-left: 10px; border-radius: 10px; border: none; padding-left: 10px" type="text" placeholder="Owner Name">
                    <div id="categoriesContainer">
                        <!-- Categories from XML will be displayed here -->
                        <!-- <input type="number" class="categoryInput" value="0">
                        <input type="number" class="categoryInput" value="0">
                        <input type="number" class="categoryInput" value="0"> -->
                    </div>
                    <input type="hidden" id="totalNumber" value="0">
                    <button style="margin-left: 10px; background-color: rgb(43, 3, 95); color: white; border: none; border-radius: 10px; width: 100%; padding: 10px; " onclick="addRecord()">Add</button>
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

    $(document).ready(function() {
        // Listen for input changes within the table body
        $('tbody').on('input', 'input', function() {
            var $row = $(this).closest('tr');
            var recordId = $row.find('td:nth-child(1)').text();
            var ownerName = $row.find('input#ownerName').val();
            var categoryValues = [];
            $row.find('input:not(#ownerName)').each(function() {
                categoryValues.push($(this).val());
            });
            updateXML(recordId, ownerName, categoryValues);
        });

        // Reload page on click outside of tbody or Enter key press outside tbody
        $(document).on('click keypress', function(event) {
            var $target = $(event.target);

            // Check if the click or keypress event occurred outside of the tbody
            if (!$target.closest('tbody').length && !$target.is('input')) {
                location.reload(); // Reload the page
            }
        });
    });
</script>

</body>
</html>

