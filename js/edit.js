import Modal from './modal.js';
import Error from './error.js';

document.addEventListener('DOMContentLoaded', function() {

    fetch(`edit.php?id=${printId}&ajax=1`)
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }
        const content = document.getElementById('content')
        const editForm = document.getElementById('edit-form')
        const loader = document.getElementById('loader')

        const titleElement = document.getElementById('title')
        const descElement = document.getElementById('desc')
        const fileElement = document.getElementById('file')
        const picElement = document.getElementById('pic')
        const previewElement = document.getElementById('preview')

        const errorTitle = document.getElementById('error-title')
        const errorDesc = document.getElementById('error-desc')
        const errorFile = document.getElementById('error-file')
        //const errorPic = document.getElementById('error-pic')
        
        const submit = document.getElementById('submit')

        titleElement.value = data.title
        descElement.value = data.desc
        previewElement.src = data.img
        new Modal('whereprints-btn', 'modal-whereprints-close', 'modal-whereprints')

        editForm.style.display = 'flex'
        loader.style.display = 'none'

        document.getElementById('pic').addEventListener('change', function(event) {
            const file = event.target.files[0]
            if (file) {
                const reader = new FileReader()
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result
                }
                reader.readAsDataURL(file)
            }
        })

        titleElement.addEventListener('input', function() {
            titleElement.style.border = '1px solid #616c7a'
            errorTitle.innerHTML = ''
        })
    
        descElement.addEventListener('input', function() {
            descElement.style.border = '1px solid #616c7a'
            errorDesc.innerHTML = ''
        })
    
        submit.addEventListener('click', function(event) {
            event.preventDefault() // Prevent the form from submitting
            const titleValue = titleElement.value
            const descValue = descElement.value
            const xmlFile = fileElement.files[0]
            const picFile = picElement.files[0]

            // Validation
            if (!titleValue) {
                errorTitle.innerHTML = 'Title field is required.'
                titleElement.style.border = '2px solid red'
            } else if (titleValue.length > 32) {
                errorTitle.innerHTML = 'Title cannot be longer than 32 characters.'
                titleElement.style.border = '2px solid red'
            }

            if (descValue.length > 512) {
                errorDesc.innerHTML = 'Description cannot be longer than 512 characters.'
                descElement.style.border = '2px solid red'        
            }

            if (xmlFile && xmlFile.type !== 'text/xml') {
                errorFile.innerHTML = 'Blueprint file must be an XML file.'
            } else if (xmlFile && xmlFile.size > 1048576) {
                errorFile.innerHTML = 'Blueprint file size is limited to 1MB.'
            }

            // output return if at least one error is present
            if (errorTitle.innerHTML || errorDesc.innerHTML || errorFile.innerHTML) {
                return
            }

            // Put loader as placeholder
            content.innerHTML = '<div class="col"><div class="loader"></div><h3 class="low-key">Processing data...</h3></div>'

            const formData = new FormData()
            formData.append('title', titleValue)
            formData.append('desc', descValue)
            if (picFile) {
                formData.append('pic', picFile)
            }

            if (xmlFile) {
                const reader = new FileReader()
                reader.onload = function(e) {
                    const xmlContent = e.target.result
                    formData.append('xmlContent', xmlContent)
                }
                reader.readAsText(xmlFile)
            } else {
                formData.append('xmlContent', data.content)
            }
            
            // AJAX request
            fetch(`edit.php?id=${printId}&ajax=2`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                window.location.replace(`print.php?id=${data.id}`)
            })
            .catch(error => {
                new Error('content', error.message)
            })
        })

        document.getElementById('whereprints-btn').addEventListener('click', function(event) {
            event.preventDefault();
        });
    })
    .catch(error => {
        new Error('content', error.message)
    })
})