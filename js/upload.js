import Modal from './modal.js';
import Error from './error.js';

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('upload-form')
    form.style.display = 'flex'

    new Modal('whereprints-btn', 'modal-whereprints-close', 'modal-whereprints')

    const titleElement = document.getElementById('title')
    const descElement = document.getElementById('desc')
    const fileElement = document.getElementById('file')
    const submit = document.getElementById('submit')
    const content = document.getElementById('content')
    const errorTitle = document.getElementById('error-title')
    const errorDesc = document.getElementById('error-desc')
    const errorFile = document.getElementById('error-file')
    //const errorPic = document.getElementById('error-pic')


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

    fileElement.addEventListener('input', function() {
        errorFile.innerHTML = ''
    })

    submit.addEventListener('click', function(event) {
        event.preventDefault() // Prevent the form from submitting
        const titleValue = titleElement.value
        const descValue = descElement.value
        const xmlFile = fileElement.files[0]
        const picFile = document.getElementById('pic').files[0]

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

        if (!xmlFile) {
            errorFile.innerHTML = 'Blueprint file is required.'
        } else if (xmlFile.type !== 'text/xml') {
            errorFile.innerHTML = 'Blueprint file must be an XML file.'
        } else if (xmlFile.size > 65535) {
            errorFile.innerHTML = 'Blueprint file size is limited to 64KB.'
        }

        // output return if at least one error is present
        if (errorTitle.innerHTML || errorDesc.innerHTML || errorFile.innerHTML) {
            return
        }

        // Put loader as placeholder
        content.innerHTML = '<div class="col"><div class="loader"></div><h3 class="low-key">Processing data...</h3></div>'

        const reader = new FileReader()
        reader.onload = function(e) {
            const xmlContent = e.target.result

            const formData = new FormData()
            formData.append('title', titleValue)
            formData.append('desc', descValue)
            formData.append('xmlContent', xmlContent)
            formData.append('pic', picFile)

            // AJAX request
            fetch(`upload.php?ajax=1`, {
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
        }
        reader.readAsText(xmlFile)
    })
    
    document.getElementById('whereprints-btn').addEventListener('click', function(event) {
        event.preventDefault();
    });
})