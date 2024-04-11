function addRecord() {
    // Get the owner name
    var ownerName = document.getElementById("ownerName").value;

    // Get all category input elements
    var categoryInputs = document.querySelectorAll("#categoriesContainer .categoryInput");

    // Calculate the total sum of category input values
    var totalSum = 0;
    var categoryValuesArray = []; // Array to store input values for each category

    categoryInputs.forEach(function(input) {
        var value = parseFloat(input.value);
        totalSum += value;
        // Store each input's value in the categoryValuesArray
        categoryValuesArray.push({ id: input.id, value: value });
    });

    // Update the hidden input with the total sum
    document.getElementById("totalNumber").value = totalSum;

    // Prepare the data to send via AJAX
    var data = {
        ownerName: ownerName,
        totalNumber: totalSum,
        categoryValues: categoryValuesArray
    };

    // Make an AJAX request to store the data
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "add_record.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log("Data successfully stored.");
                // Reload the page after a successful response
                window.location.reload();
            } else {
                console.log("Error storing data.");
                // Handle error scenario if needed
            }
        }
    };

    // Convert data to JSON and send it
    xhr.send(JSON.stringify(data));
}
