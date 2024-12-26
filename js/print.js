document.addEventListener('DOMContentLoaded', function() {
    fetch(`print.php?id=${printId}&ajax=1`)
    .then(response => response.json())
    .then(data => {
        document.getElementById('content').innerHTML = `
            <div class="picture">
                <img src="${data.img}" alt="${data.title}">
            </div>
            <div class="vert-line"></div>
            <div class="section">
                <div class="data">
                    <h1>${data.title}</h1>
                    <p class="low-key">${data.username} · ${data.relCreatedAt}</p>
                    <p>${data.desc}</p>
                </div>
                <button class="btn-sm">Download print</button>
            </div>
        `;
    })
    .catch(error => {
        document.getElementById('content').innerHTML = `
            <h1>Print not found</h1>
        `;
    })
});