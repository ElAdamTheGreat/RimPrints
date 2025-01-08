document.addEventListener('DOMContentLoaded', function() {
    fetch(`print.php?id=${printId}&ajax=1`)
    .then(response => response.json())
    .then(data => {
        document.getElementById('content').innerHTML = `
            <div class="picture">
                <img src="${data.img}" alt="${data.title} image">
            </div>
            <div class="vert-line"></div>
            <div class="section">
                <div class="data">
                    <h1>${data.title}</h1>
                    <p class="low-key">${data.username} · ${data.relCreatedAt}</p>
                    <p>${data.desc}</p>
                </div>
                <a class="btn-sm" id="download-btn">Download print</a>
            </div>
        `;
        
        // Download print button
        let fileTitle = data.title + '.xml'
        var myFile = new Blob([data.content], {type: 'text/plain'});
        window.URL = window.URL || window.webkitURL;
        let downloadBtn = document.getElementById('download-btn');
        downloadBtn.setAttribute("href", window.URL.createObjectURL(myFile));
        downloadBtn.setAttribute("download", fileTitle);

        //console.log(data.createdAt)
    })
    .catch(error => {
        document.getElementById('content').innerHTML = `
            <h1>Print not found</h1>
        `;
    })
});