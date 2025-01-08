// filepath: /c:/WebyVS/RimPrints/js/modal.js
class Modal {
    constructor(openButtonId, closeButtonId, modalId) {
        this.modalOpen = document.getElementById(openButtonId);
        this.modalClose = document.getElementById(closeButtonId);
        this.modal = document.getElementById(modalId);

        this.addEventListeners();
    }

    addEventListeners() {
        if (this.modalOpen) {
            this.modalOpen.addEventListener('click', () => this.openModal());
        }
        if (this.modalClose) {
            this.modalClose.addEventListener('click', () => this.closeModal());
        }
    }

    openModal() {
        this.modal.style.display = 'flex';
    }

    closeModal() {
        this.modal.style.display = 'none';
    }
}

export default Modal;