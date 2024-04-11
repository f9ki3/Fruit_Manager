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
        <div>
            <hr>
            <h5>Add Category</h5>
            <div style="display: flex; flex-direction: row;">
                <input id="category" type="text" style="margin-left: 10px;" placeholder="Category">
                <button style="margin-left: 10px;" onclick="addCategory()">Add</button>
            </div>
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
    <hr>

    <div >
        <table id="basketTableContainer" style="text-align: center;">
            <?php include 'header.php'?>
        </table>
    </div>

    

    <script src="addCategory.js"></script>
    <script src="display_category.js"></script>
    <script src="addRecord.js"></script>
    <script src="delete.js"></script>
</body>
</html>

