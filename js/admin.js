import Modal from './modal.js';
import OutputError from './error.js';

document.addEventListener('DOMContentLoaded', function() {
    fetch(`admin.php?&ajax=1`)
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }

        const users = data;
        const table = document.getElementById('user-table');
        users.forEach(user => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${user.username}</td>
                <td>${user.email}</td>
                <td>${user.prints}</td>
                <td>${user.role}</td>
                <td>
                    ${user.role == 'admin' ? 
                        `<button class="btn-sm demote-btn" data-user-id="${user.userId}">
                            <span class="material-symbols-outlined">keyboard_arrow_down</span> Demote
                        </button>
                        <button class="btn-sm demote-btn-mobile" data-user-id="${user.userId}">
                            <span class="material-symbols-outlined">keyboard_arrow_down</span>
                        </button>` : 
                        `<button class="btn-sm promote-btn" data-user-id="${user.userId}">
                            <span class="material-symbols-outlined">keyboard_arrow_up</span> Promote
                        </button>
                        <button class="btn-sm promote-btn-mobile" data-user-id="${user.userId}">
                            <span class="material-symbols-outlined">keyboard_arrow_up</span>
                        </button>`
                    }
                    <button class="btn-sm delete-btn" data-user-id="${user.userId}">
                        <span class="material-symbols-outlined">delete</span> Delete
                    </button>
                    <button class="btn-sm delete-btn-mobile" data-user-id="${user.userId}">
                        <span class="material-symbols-outlined">delete</span>
                    </button>
                </td>
            `;
            table.appendChild(row);
        });
        document.getElementById('loading').style.display = 'none';
        document.getElementById('section').style.display = 'flex';

        // Add event listeners for delete buttons
        const deleteModal = new Modal(null, 'modal-userDelete-close', 'modal-userDelete');
        document.querySelectorAll('.delete-btn, .delete-btn-mobile').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                deleteModal.openModal();

                const confirmDeleteButton = document.getElementById('modal-userDelete-confirm');
                confirmDeleteButton.onclick = function() {
                    fetch(`admin.php?&ajax=2`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `userId=${userId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelector(`button[data-user-id="${userId}"]`).closest('tr').remove();
                            deleteModal.closeModal();
                        } else {
                            alert('Failed to delete user.');
                        }
                    })
                    .catch(error => {
                        alert('Error: ' + error.message);
                    });
                };
            });
        });

        // Add event listeners for promote buttons
        const promoteModal = new Modal(null, 'modal-userPromote-close', 'modal-userPromote');
        document.querySelectorAll('.promote-btn, .promote-btn-mobile').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                promoteModal.openModal();

                const confirmPromoteButton = document.getElementById('modal-userPromote-confirm');
                confirmPromoteButton.onclick = function() {
                    fetch(`admin.php?&ajax=3`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `userId=${userId}&role=admin`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Failed to promote user.');
                        }
                    })
                    .catch(error => {
                        alert('Error: ' + error.message);
                    });
                };
            });
        });

        // Add event listeners for demote buttons
        const demoteModal = new Modal(null, 'modal-userDemote-close', 'modal-userDemote');
        document.querySelectorAll('.demote-btn, .demote-btn-mobile').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                demoteModal.openModal();

                const confirmDemoteButton = document.getElementById('modal-userDemote-confirm');
                confirmDemoteButton.onclick = function() {
                    fetch(`admin.php?&ajax=3`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `userId=${userId}&role=user`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Failed to demote user.');
                        }
                    })
                    .catch(error => {
                        alert('Error: ' + error.message);
                    });
                };
            });
        });
    })
    .catch(error => {
        new OutputError('content', error.message)
    });
});