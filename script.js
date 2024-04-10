document.addEventListener('DOMContentLoaded', function() {
    // Entry point when DOM content is loaded
    loadBasketData(); // Load initial basket data from the backend

    // Function to load basket data from the backend
    function loadBasketData() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'backend.php?action=get_data', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    displayBasketData(data); // Display the loaded data
                } else {
                    console.error('Failed to load data');
                }
            }
        };
        xhr.send();
    }

    // Function to display basket data in HTML table
    function displayBasketData(data) {
        const tableContainer = document.getElementById('basketTableContainer');
        tableContainer.innerHTML = ''; // Clear existing content

        const table = document.createElement('table');
        const headerRow = table.insertRow();
        headerRow.innerHTML = '<th>Basket No</th><th>Basket Owner</th><th>Fruit 1</th><th>Fruit 2</th><th>Fruit 3</th><th>Fruit 4</th><th>Total Fruits</th><th>Action</th>';

        // Loop through each basket data and create table rows
        data.forEach(basket => {
            const row = table.insertRow();
            row.innerHTML = `
                <td>${basket.basketNo}</td>
                <td><input class="owner-input" type="text" value="${basket.basketOwner}"></td>
                <td><input class="fruit-input" type="number" value="${basket.fruits['Fruit 1'] || 0}"></td>
                <td><input class="fruit-input" type="number" value="${basket.fruits['Fruit 2'] || 0}"></td>
                <td><input class="fruit-input" type="number" value="${basket.fruits['Fruit 3'] || 0}"></td>
                <td><input class="fruit-input" type="number" value="${basket.fruits['Fruit 4'] || 0}"></td>
                <td>${calculateTotalFruits(basket.fruits)}</td>
                <td><button class="delete-button" data-basket-no="${basket.basketNo}">Delete</button></td>`;

            // Apply color classes based on fruit quantities
            if (basket.fruits['Fruit 1'] > 5 || basket.fruits['Fruit 2'] > 5 || basket.fruits['Fruit 3'] > 5 || basket.fruits['Fruit 4'] > 5) {
                row.classList.add('blue');
            } else {
                row.classList.add('red');
            }

            // Apply yellow background for the row with the maximum total fruits
            const maxFruits = Math.max(...Object.values(basket.fruits));
            if (calculateTotalFruits(basket.fruits) === maxFruits) {
                row.classList.add('yellow');
            }

            // Attach event listener for owner input change
            row.querySelector('.owner-input').addEventListener('change', function() {
                updateBasketOwner(basket.basketNo, this.value);
            });

            // Attach event listener for fruit quantity input change
            row.querySelectorAll('.fruit-input').forEach(input => {
                input.addEventListener('change', function() {
                    const fruitName = this.parentElement.cellIndex === 2 ? 'Fruit 1' :
                                      this.parentElement.cellIndex === 3 ? 'Fruit 2' :
                                      this.parentElement.cellIndex === 4 ? 'Fruit 3' :
                                      'Fruit 4';
                    updateFruitQuantity(basket.basketNo, fruitName, this.value);
                });
            });

            // Attach event listener for delete button
            const deleteButton = row.querySelector('.delete-button');
            deleteButton.addEventListener('click', function() {
                const basketNo = this.getAttribute('data-basket-no');
                console.log('Delete clicked for basketNo:', basketNo);
                deleteBasket(basketNo);
            });
        });

        tableContainer.appendChild(table); // Append the table to the container
    }

    // Function to calculate total fruits for a basket
    function calculateTotalFruits(fruits) {
        return Object.values(fruits).reduce((total, fruit) => total + parseInt(fruit), 0);
    }

    // Function to update basket owner via AJAX
    function updateBasketOwner(basketNo, newOwner) {
        sendRequest('POST', 'backend.php?action=update_basket_owner', `basketNo=${basketNo}&newOwner=${encodeURIComponent(newOwner)}`);
    }

    // Function to update fruit quantity via AJAX
    function updateFruitQuantity(basketNo, fruitName, newQuantity) {
        sendRequest('POST', 'backend.php?action=update_fruit_quantity', `basketNo=${basketNo}&fruitName=${fruitName}&newQuantity=${newQuantity}`);
    }

    // Function to delete basket via AJAX
    function deleteBasket(basketNo) {
        sendRequest('POST', 'backend.php?action=delete_basket', `basketNo=${basketNo}`);
    }

    // Generic function to send AJAX requests
    function sendRequest(method, url, params) {
        const xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log('Request successful');
                    loadBasketData(); // Refresh data after successful request
                } else {
                    console.error('Request failed');
                }
            }
        };
        xhr.send(params);
    }
});

function add_record() {
    // Retrieve input values
    const ownerName = document.querySelector('input[placeholder="Owner Name"]').value;
    const fruits = [];

    // Loop through fruit input fields to gather fruit names
    for (let i = 1; i <= 4; i++) {
        const fruitName = document.querySelector(`input[placeholder="Fruit ${i}"]`).value;
        if (fruitName.trim() !== '') {
            fruits.push(fruitName);
        }
    }

    // Construct XML data for the new basket
    let xmlData = `<basket>\n`;
    xmlData += `    <basketOwner>${ownerName}</basketOwner>\n`;
    
    if (fruits.length > 0) {
        xmlData += `    <fruits>\n`;
        fruits.forEach((fruitName) => {
            xmlData += `        <fruit>\n`;
            xmlData += `            <name>${fruitName}</name>\n`;
            xmlData += `            <quantity>1</quantity>\n`; // Default quantity
            xmlData += `        </fruit>\n`;
        });
        xmlData += `    </fruits>\n`;
    }
    
    xmlData += `</basket>\n`;
    fetch('backend.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/xml'
        },
        body: xmlData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Basket added successfully!');
            // Optionally, update UI or perform additional actions upon success
        } else {
            alert('Failed to add basket. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error adding basket:', error);
        alert('An error occurred while adding the basket. Please try again later.');
    });
}
