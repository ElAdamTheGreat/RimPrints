document.addEventListener('DOMContentLoaded', function() {
    class Modal {
        constructor(openButtonId, closeButtonId, modalId) {
            this.modalOpen = document.getElementById(openButtonId);
            this.modalClose = document.getElementById(closeButtonId);
            this.modal = document.getElementById(modalId);

            this.addEventListeners();
        }

        addEventListeners() {
            this.modalOpen.addEventListener('click', () => this.openModal());
            this.modalClose.addEventListener('click', () => this.closeModal());
        }

        openModal() {
            this.modal.style.display = 'flex';
        }

        closeModal() {
            this.modal.style.display = 'none';
        }
    }

    const modal = new Modal('modal-open', 'modal-close', 'modal');
});