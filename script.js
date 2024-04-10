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
            row.innerHTML = `<td>${basket.basketNo}</td>
                         <td><input style="border: none; background-color: transparent; text-align: center" type="text" value="${basket.basketOwner}" onchange="updateBasketOwner(${basket.basketNo}, this.value)"></td>
                         <td><input style="border: none; background-color: transparent; text-align: center" type="number" value="${basket.fruits['Fruit 1'] || 0}" onchange="updateFruitQuantity(${basket.basketNo}, 'Fruit 1', this.value)"></td>
                         <td><input style="border: none; background-color: transparent; text-align: center" type="number" value="${basket.fruits['Fruit 2'] || 0}" onchange="updateFruitQuantity(${basket.basketNo}, 'Fruit 2', this.value)"></td>
                         <td><input style="border: none; background-color: transparent; text-align: center" type="number" value="${basket.fruits['Fruit 3'] || 0}" onchange="updateFruitQuantity(${basket.basketNo}, 'Fruit 3', this.value)"></td>
                         <td><input style="border: none; background-color: transparent; text-align: center" type="number" value="${basket.fruits['Fruit 4'] || 0}" onchange="updateFruitQuantity(${basket.basketNo}, 'Fruit 4', this.value)"></td>
                         <td>${calculateTotalFruits(basket.fruits)}</td>
                         <td><button onclick="deleteBasket(${basket.basketNo})">Delete</button></td>`;

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
        });

        tableContainer.appendChild(table); // Append the table to the container
    }

    // Function to calculate total fruits for a basket
    function calculateTotalFruits(fruits) {
        return Object.values(fruits).reduce((total, fruit) => total + parseInt(fruit), 0);
    }

    // Function to update basket owner via AJAX
    function updateBasketOwner(basketNo, newOwner) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'backend.php?action=update_basket_owner', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log('Basket owner updated successfully');
                    loadBasketData(); // Refresh data after successful update
                } else {
                    console.error('Failed to update basket owner');
                }
            }
        };
        xhr.send(`basketNo=${basketNo}&newOwner=${encodeURIComponent(newOwner)}`);
    }

    // Function to update fruit quantity via AJAX
    function updateFruitQuantity(basketNo, fruitName, newQuantity) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'backend.php?action=update_fruit_quantity', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log('Fruit quantity updated successfully');
                    loadBasketData(); // Refresh data after successful update
                } else {
                    console.error('Failed to update fruit quantity');
                }
            }
        };
        xhr.send(`basketNo=${basketNo}&fruitName=${fruitName}&newQuantity=${newQuantity}`);
    }

    // Function to delete basket via AJAX
    function deleteBasket(basketNo) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'backend.php?action=delete_basket', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log('Basket deleted successfully');
                    loadBasketData(); // Refresh data after successful delete
                } else {
                    console.error('Failed to delete basket');
                }
            }
        };
        xhr.send(`basketNo=${basketNo}`);
    }
});
