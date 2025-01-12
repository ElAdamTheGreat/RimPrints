import Modal from './modal.js';
import Error from './error.js';

document.addEventListener('DOMContentLoaded', function() {
    const dataElement = document.getElementById('data')
    const uploadBtn = document.getElementById('upload-btn')
    const modalUpload = new Modal('modal-open', 'modal1-close', 'modal-upload')
    let isSignedIn

    uploadBtn.addEventListener('click', function() {
        fetch('server/session.php')
        .then(response => response.json())
        .then(data => {
            isSignedIn = data.isSignedIn;

            if (isSignedIn) {
                console.log('User is signed in. Redirecting to upload page...');
                window.location.href = 'upload.php';
            } else {
                console.log("Access denied.");
                modalUpload.openModal();
            }
        });
    });

    fetch(`index.php?page=${currentPage}&ajax=1`)
    .then(response => response.json())
    .then(data => {
        if (data === null) {
            dataElement.innerHTML = 'No prints found.';
            return;
        }

        const totalPages = data.totalPages;
        const prints = data.prints;
        const currentPage = parseInt(new URLSearchParams(window.location.search).get('page')) || 1;

        dataElement.innerHTML = ''; // Clear the loading message

        const printGrid = document.createElement('div');
        printGrid.className = 'print-grid';
        dataElement.appendChild(printGrid);

        prints.forEach(print => {
            const printCard = document.createElement('a');
            printCard.href = `print.php?id=${print.id}`;
            printCard.className = 'card';
            printCard.innerHTML = `
                <div class="cardimg">
                    <img src="${print.img}" alt="${print.title} image">
                </div>
                <h2>${print.title}</h2>
                <div class="cardinfo">
                    <p class="low-key">by ${print.username}</p>
                </div>
            `;
            printGrid.appendChild(printCard);
        });

        // Pagination
        setupPagination(currentPage, totalPages);
    })
    .catch(error => {
        new Error('data', error.message)
    })
});

function setupPagination(currentPage, totalPages) {
    if (totalPages <= 1) {
        return;
    }

    // Cache elements to avoid repetitive DOM lookups
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const pagination = document.getElementById('pagination');
    const pageNumber = document.getElementById('page-number');

    // Set initial button states
    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === totalPages;

    // Update the pagination display
    pagination.style.display = 'flex';
    pageNumber.textContent = `${currentPage} / ${totalPages}`;

    // Remove old listeners if needed
    prevBtn.replaceWith(prevBtn.cloneNode(true)); // Clears all attached listeners
    nextBtn.replaceWith(nextBtn.cloneNode(true));

    // Rebind updated buttons after clone
    const newPrevBtn = document.getElementById('prev-btn');
    const newNextBtn = document.getElementById('next-btn');

    // Attach event listeners
    newPrevBtn.addEventListener('click', function () {
        if (currentPage > 1) {
            window.location.href = `index.php?page=${currentPage - 1}`;
        }
    });

    newNextBtn.addEventListener('click', function () {
        if (currentPage < totalPages) {
            window.location.href = `index.php?page=${currentPage + 1}`;
        }
    });
}
