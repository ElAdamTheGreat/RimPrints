document.addEventListener('DOMContentLoaded', function() {
    const dataElement = document.getElementById('data');
    
    fetch(`index.php?ajax=1`)
    .then(response => response.json())
    .then(data => {
        dataElement.innerHTML = ''; // Clear the loading message

        const printGrid = document.createElement('div');
        printGrid.className = 'print-grid';
        dataElement.appendChild(printGrid);

        data.forEach(print => {
            const printCard = document.createElement('a');
            printCard.href = `print.php?id=${print.id}`;
            printCard.className = 'card';
            printCard.innerHTML = `
                <img src="${print.img}" alt="placeholder">
                <h2>${print.title}</h2>
                <div class="cardinfo">
                    <p class="low-key">by ${print.username}</p>
                </div>
            `;
            printGrid.appendChild(printCard);
        });
    })
    .catch(error => {
        console.error('Error fetching data:', error);
        dataElement.innerHTML = '<p>Error loading data. Please try again later.</p>';
    });
});