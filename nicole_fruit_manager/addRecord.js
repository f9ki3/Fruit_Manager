function addRecord() {
    var ownerName = document.getElementById("ownerName").value;
    var categoryInputs = document.querySelectorAll("#categoriesContainer .categoryInput");
    var totalSum = 0;
    var categoryValuesArray = [];

    // Check if any category input is empty or zero
    var isValid = Array.from(categoryInputs).every(function(input) {
        var value = parseFloat(input.value);
        if (isNaN(value) || value <= 0) {
            return false; // Invalid input found
        }
        totalSum += value;
        categoryValuesArray.push({ id: input.id, value: value });
        return true;
    });

    if (!isValid) {
        alert("Please fill in valid category values.");
        return; // Prevent adding record if category values are invalid
    }

    document.getElementById("totalNumber").value = totalSum;

    // Check if categories.xml exists before proceeding
    var checkXhr = new XMLHttpRequest();
    checkXhr.open("HEAD", "categories.xml", true);
    checkXhr.onreadystatechange = function() {
        if (checkXhr.readyState === XMLHttpRequest.DONE) {
            if (checkXhr.status === 200) {
                // categories.xml exists, proceed with adding record
                storeRecord(ownerName, totalSum, categoryValuesArray);
                window.location.reload();
            } else {
                // categories.xml does not exist, prevent adding record
                alert("You must add categories first");
                // Optionally display a message to the user or handle the error appropriately
            }
        }
    };
    checkXhr.send();
}

function storeRecord(ownerName, totalSum, categoryValuesArray) {
    var data = {
        ownerName: ownerName,
        totalNumber: totalSum,
        categoryValues: categoryValuesArray
    };

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "add_record.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log("Data successfully stored.");
                window.location.reload();
            } else {
                console.log("Error storing data.");
                // Handle error scenario if needed
            }
        }
    };

    xhr.send(JSON.stringify(data));
}
