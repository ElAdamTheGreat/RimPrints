document.addEventListener('DOMContentLoaded', function() {
    const submit = document.getElementById('submit');
    const content = document.getElementById('content');

    document.getElementById('pic').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    submit.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the form from submitting

        const titleValue = document.getElementById('title').value;
        const descValue = document.getElementById('desc').value;
        const xmlFile = document.getElementById('file').files[0];
        const picFile = document.getElementById('pic').files[0];

        // Put loader as placeholder
        content.innerHTML = '<div class="col"><div class="loader"></div><h3 class="low-key">Processing data...</h3></div>';

        const reader = new FileReader();
        reader.onload = function(e) {
            const xmlContent = e.target.result;

            const formData = new FormData();
            formData.append('title', titleValue);
            formData.append('desc', descValue);
            formData.append('xmlContent', xmlContent);
            formData.append('pic', picFile);

            // AJAX request
            fetch(`upload.php?ajax=1`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                window.location.replace(`print.php?id=${data.id}`);
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                content.innerHTML = '<p>Error loading data. Please try again later.</p>';
            });
        };
        reader.readAsText(xmlFile);
    });
});