function add() {
    // Retrieve input values
    var ownerName = document.getElementById("ownerName").value;
    var apple = document.getElementById("apple").value;
    var banana = document.getElementById("banana").value;
    var mango = document.getElementById("mango").value;
    var guava = document.getElementById("guava").value;

    // Prepare fruits array
    var fruits = [];
    if (apple.trim() !== "") fruits.push(apple);
    if (banana.trim() !== "") fruits.push(banana);
    if (mango.trim() !== "") fruits.push(mango);
    if (guava.trim() !== "") fruits.push(guava);

    // Validate ownerName and fruits array
    if (ownerName.trim() === "" || fruits.length === 0) {
        alert("Please enter owner name and at least one fruit.");
        return;
    }

    // Prepare data object to send via AJAX
    var data = {
        ownerName: ownerName,
        fruits: fruits
    };

    // Send data using fetch API (modern approach)
    fetch('add.php?action=insert_basket', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (response.ok) {
            return response.json(); // Parse response JSON
        }
        throw new Error('Network response was not ok.');
    })
    .then(result => {
        if (result.success) {
            alert('Record added successfully: ' + result.message);
            // Optionally, you can perform additional actions upon success
        } else {
            alert('Failed to add record: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Error adding record:', error);
        alert('Error adding record. Please try again.');
    });
}


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
        headerRow.innerHTML = '<th>Basket No</th><th>Basket Owner</th><th>Apple</th><th>Banana</th><th>Mango</th><th>Guava</th><th>Total Fruits</th><th>Action</th>';

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


