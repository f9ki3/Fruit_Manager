document.addEventListener('DOMContentLoaded', function() {
    loadBasketData();

    function loadBasketData() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'backend.php?action=get_data', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    displayBasketData(data);
                } else {
                    console.error('Failed to load data');
                }
            }
        };
        xhr.send();
    }

    function displayBasketData(data) {
        const tableContainer = document.getElementById('basketTableContainer');
        tableContainer.innerHTML = ''; // Clear existing content

        const table = document.createElement('table');
        const headerRow = table.insertRow();
        headerRow.innerHTML = '<th>Basket No</th><th>Basket Owner</th><th>Fruit 1</th><th>Fruit 2</th><th>Fruit 3</th><th>Fruit 4</th><th>Total Fruits</th>';

        data.forEach(basket => {
            const row = table.insertRow();
            row.innerHTML = `<td>${basket.basketNo}</td><td>${basket.basketOwner}</td>
                             <td>${basket.fruits['Fruit 1'] || 0}</td>
                             <td>${basket.fruits['Fruit 2'] || 0}</td>
                             <td>${basket.fruits['Fruit 3'] || 0}</td>
                             <td>${basket.fruits['Fruit 4'] || 0}</td>
                             <td>${calculateTotalFruits(basket.fruits)}</td>`;

            if (basket.fruits['Fruit 1'] > 5 || basket.fruits['Fruit 2'] > 5 || basket.fruits['Fruit 3'] > 5 || basket.fruits['Fruit 4'] > 5) {
                row.classList.add('blue');
            } else {
                row.classList.add('red');
            }

            const maxFruits = Math.max(...Object.values(basket.fruits));
            if (calculateTotalFruits(basket.fruits) === maxFruits) {
                row.classList.add('yellow');
            }
        });

        tableContainer.appendChild(table);
    }

    function calculateTotalFruits(fruits) {
        return Object.values(fruits).reduce((total, fruit) => total + parseInt(fruit), 0);
    }

    // Example usage of CRUD operations
    function addBasket(basketNo, basketOwner) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'backend.php?action=add_basket', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log('Basket added successfully');
                    loadBasketData(); // Refresh data after addition
                } else {
                    console.error('Failed to add basket');
                }
            }
        };
        xhr.send(`basketNo=${basketNo}&basketOwner=${basketOwner}`);
    }

    function updateFruitQuantity(basketNo, fruitName, newQuantity) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'backend.php?action=update_fruit_quantity', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log('Fruit quantity updated successfully');
                    loadBasketData(); // Refresh data after update
                } else {
                    console.error('Failed to update fruit quantity');
                }
            }
        };
        xhr.send(`basketNo=${basketNo}&fruitName=${fruitName}&newQuantity=${newQuantity}`);
    }

    function deleteBasket(basketNo) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'backend.php?action=delete_basket', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log('Basket deleted successfully');
                    loadBasketData(); // Refresh data after deletion
                } else {
                    console.error('Failed to delete basket');
                }
            }
        };
        xhr.send(`basketNo=${basketNo}`);
    }

    // Example usage:
    // addBasket(3, 'John Doe'); // Add a new basket
    // updateFruitQuantity(3, 'Fruit 1', 10); // Update quantity of Fruit 1 in basket 3
    // deleteBasket(2); // Delete basket with Basket No. 2
});
