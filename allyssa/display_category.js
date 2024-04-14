document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
});

function loadCategories() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            if (this.status == 200) {
                displayCategories(this.responseXML);
            } else if (this.status == 404) {

            }
        }
    };
    xmlhttp.open("GET", "categories.xml", true);
    xmlhttp.send();
}

function displayCategories(xml) {
    var categoriesContainer = document.getElementById("categoriesContainer");
    categoriesContainer.innerHTML = ""; // Clear previous content

    var categories = xml.getElementsByTagName("category");
    for (var i = 0; i < categories.length; i++) {
        var categoryName = categories[i].textContent; // Use textContent to get the node value

        // Create a new input element
        var inputElement = document.createElement("input");
        inputElement.setAttribute("type", "text");
        inputElement.setAttribute("placeholder", categoryName);
        inputElement.setAttribute("class", 'categoryInput');
        inputElement.style.marginLeft = "10px";
        inputElement.style.borderRadius = "10px"; // Set border radius
        inputElement.style.border = "1px solid gray"; // Set border
        inputElement.style.padding= "10px"; // Set padding-left

        // Append the input element to the categoriesContainer
        categoriesContainer.appendChild(inputElement);
    }
}
