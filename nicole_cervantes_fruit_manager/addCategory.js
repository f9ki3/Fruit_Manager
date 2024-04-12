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
