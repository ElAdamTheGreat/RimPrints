import Modal from './modal.js';
import Error from './error.js';

document.addEventListener('DOMContentLoaded', function() {

    fetch(`print.php?id=${printId}&ajax=1`)
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }
        document.getElementById('content').innerHTML = `
            <div class="picture">
                <img src="${data.img}" alt="${data.title} image">
            </div>
            <div class="vert-line"></div>
            <div class="section">
                <div class="data">
                    <h1>${data.title}</h1>
                    <p class="low-key">${data.username} · created ${data.relCreatedAt} ${data.relCreatedAt != data.relUpdatedAt ? `(edited)` : ``}</p>
                    <p>${data.desc}</p>
                </div>
                <a class="btn" id="download-btn">Download print</a>

                ${data.showActions ? `
                <div class="actions-container">
                    <div class="toggle-actions">
                        <span class="material-symbols-outlined" id="arrow-icon">keyboard_arrow_down</span>
                        <button id="toggle-actions" class="link-button">Show actions</button>
                    </div>
                    <div class="actions">
                        <a href="edit.php?id=${printId}" class="btn">Edit</a>
                        <button id="printDelete-btn" class="btn-red">Delete</button>
                    </div>
                </div>
                ` : ''}
            </div>
        `;

        // Modal for delete print
        new Modal('printDelete-btn', 'modal-printDelete-close', 'modal-printDelete')

        // Toggle actions, but only if its defined in the data
        if (data.showActions === true) {
            const toggleActions = document.getElementById('toggle-actions');
            const arrowIcon = document.getElementById('arrow-icon');
            const deleteBtn = document.getElementById('modal-printDelete-confirm');
            const modalButtons = document.getElementById('delete-buttons');

            toggleActions.addEventListener('click', function() {
                let actions = document.querySelector('.actions');
                if (actions.style.display == 'flex') {
                    actions.style.display = 'none';
                    toggleActions.innerHTML = 'Show actions';
                    arrowIcon.classList.remove('rotate-180');
                } else {
                    actions.style.display = 'flex';
                    toggleActions.innerHTML = 'Hide actions';
                    arrowIcon.classList.add('rotate-180');
                }
            });

            deleteBtn.addEventListener('click', function() {
                modalButtons.innerHTML = '<div class="small-loader"></div>'
                
                console.log('Sending AJAX request for print delete...');
                fetch(`print.php?id=${printId}&ajax=2`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${printId}`
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Received response:', data);
                    if (data.success) {
                        window.location.href = 'index.php';
                    } else {
                        modalButtons.innerHTML = `
                            <p>${data.error}</p>
                            <button id="modal-printDelete-close" class="btn">Close</button>
                        `;
                    }
                });
            })
        }

        // Download print button
        let fileTitle = data.title + '.xml';
        var myFile = new Blob([data.content], {type: 'text/plain'});
        window.URL = window.URL || window.webkitURL;
        let downloadBtn = document.getElementById('download-btn');
        downloadBtn.setAttribute("href", window.URL.createObjectURL(myFile));
        downloadBtn.setAttribute("download", fileTitle);

        //console.log(data.createdAt)
    })
    .catch(error => {
        new Error('content', error.message)
    });
});