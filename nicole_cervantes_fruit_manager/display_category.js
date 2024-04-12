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
        var categoryName = categories[i].childNodes[0].nodeValue;

        // Create a new input element
        var inputElement = document.createElement("input");
        inputElement.setAttribute("type", "text");
        inputElement.setAttribute("placeholder", categoryName);
        inputElement.setAttribute("class", 'categoryInput');
        inputElement.style.marginLeft = "10px";

        // Append the input element to the categoriesContainer
        categoriesContainer.appendChild(inputElement);
    }
}

