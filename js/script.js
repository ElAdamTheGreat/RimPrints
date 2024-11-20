modalOpen = document.getElementById('modal-open');
modalClose = document.getElementById('modal-close');
modal = document.getElementById('modal');

modalOpen.addEventListener('click', function() {
    modal.style.display = 'flex';
});

modalClose.addEventListener('click', function() {
    modal.style.display = 'none';
});