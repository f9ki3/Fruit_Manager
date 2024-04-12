function addCategory() {
    var categoryInput = document.getElementById('category');
    var category = categoryInput.value.trim(); // Get the trimmed value from the input field

    // Check if category input is empty
    if (category === '') {
        alert('Please enter a category.');
        return; // Exit function early if input is empty
    }

    // Function to check if "alyssa.xml" file is empty
    function checkAlyssaFile() {
        $.ajax({
            type: 'GET',
            url: 'checkAlyssaFile.php', // Modify to point to script checking alyssa.xml
            success: function(response) {
                var isFileEmpty = (response.trim() === '');
                
                if (!isFileEmpty) {
                    alert('Cannot add category basket record must be empty');
                } else {
                    // "alyssa.xml" is empty, proceed to add the category
                    addCategoryRequest();
                    window.location.reload(); // Reload the page after successful addition
                }
            },
            error: function(xhr, status, error) {
                console.error('Error checking alyssa.xml:', error);
            }
        });
    }

    // Function to add category after confirming "alyssa.xml" is empty
    function addCategoryRequest() {
        // Make an AJAX request to add the category
        $.ajax({
            type: 'POST',
            url: 'addCategory.php',
            data: { category: category },
            success: function(response) {
                console.log(response); // Log the response to the console
                window.location.reload(); // Reload the page after successful addition
            },
            error: function(xhr, status, error) {
                console.error('Error adding category:', error);
            }
        });

        // Clear input field after successful submission (optional)
        categoryInput.value = '';
    }

    // Check if "alyssa.xml" file is empty before proceeding
    checkAlyssaFile();
}
