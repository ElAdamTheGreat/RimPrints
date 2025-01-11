document.addEventListener('DOMContentLoaded', function() { 
    const usernameElement = document.getElementById('username');
    const emailElement = document.getElementById('email');
    const passwordElement = document.getElementById('password');
    const passwordCheckElement = document.getElementById('password-check');

    const errorUsername = document.getElementById('error-username');
    const infoUsername = document.getElementById('info-username');
    const errorEmail = document.getElementById('error-email');
    const errorPassword = document.getElementById('error-password');
    const errorPasswordCheck = document.getElementById('error-password-check');
    const error = document.getElementById('error');

    const submitBtn = document.getElementById('submit')


    // Does user with this username already exist?
    let usernameCheck = false;
    let usernameTimeout;
    usernameElement.addEventListener('input', function() {
        if (usernameTimeout) {
            clearTimeout(usernameTimeout);
        }
        usernameTimeout = setTimeout(validateUsername, 500);
    });

    let passwordCheck = false;
    let passwordTimeout;
    passwordCheckElement.addEventListener('input', function() {
        submitBtn.setAttribute('disabled', 'true');
        passwordCheck = false
        if (passwordTimeout) {
            clearTimeout(passwordTimeout);
        }
        passwordTimeout = setTimeout(validatePassword, 500);
    });

    function validateUsername() {
        // validate username length
        if (usernameElement.value.length === 0) {
            wrongUsername('')
        } else if (usernameElement.value.length < 4) {
            wrongUsername('Username is too short.')
        } else if (usernameElement.value.length > 16) {
            wrongUsername('Username is too long!', true)
        } else {
            // Send req
            fetch(`signup.php?ajax=1`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `username=${usernameElement.value}`
            })
            .then(response => response.json())
            .then(data => {
                // evaluate response
                if (data.taken) {
                    wrongUsername('Username is already taken.', true)
                } else {
                    usernameCheck = true
                    if (passwordCheck === true) {
                        submitBtn.removeAttribute('disabled');
                    }
                    usernameElement.style.border = '1px solid #616c7a'
                    infoUsername.innerHTML = 'What a wonderful username!'
                    errorUsername.innerHTML = ''
                }
            })
        }
    }

    function validatePassword() {
        if (passwordElement.value === passwordCheckElement.value) {
            passwordCheck = true
            if (usernameCheck === true) {
                submitBtn.removeAttribute('disabled');
            }
            passwordCheckElement.style.border = '1px solid #616c7a'
            errorPasswordCheck.innerHTML = ''
        } else {
            passwordCheckElement.style.border = '2px solid red'
            errorPasswordCheck.innerHTML = 'Passwords do not match.'
        }
    }

    function wrongUsername(text, border) {
        usernameCheck = false
        submitBtn.setAttribute('disabled', 'true');
        if (border) {
            usernameElement.style.border = '2px solid red'
        } else {
            usernameElement.style.border = '1px solid #616c7a'
        }
        errorUsername.innerHTML = text
        infoUsername.innerHTML = ''
    }


    emailElement.addEventListener('input', function() {
        emailElement.style.border = '1px solid #616c7a'
        errorEmail.innerHTML = ''
    })

    passwordElement.addEventListener('input', function() {
        passwordElement.style.border = '1px solid #616c7a'
        errorPassword.innerHTML = ''
    })

    document.getElementById('registration-form').addEventListener('submit', function(event) {
        event.preventDefault();

        // Client-side validation
        if (!validateEmail(emailElement.value) && emailElement.value !== undefined) {
            errorEmail.innerHTML = "Unvalid email address.";
            emailElement.style.border = '2px solid red'
        }
        if (passwordElement.value.length < 4) {
            errorPassword.innerHTML = "Password is too short!";
            passwordElement.style.border = '2px solid red'
        }

        // output return if at least one error is present
        if (usernameCheck === false || passwordCheck === false || errorEmail.innerHTML || errorPassword.innerHTML) {
            console.log('Error present, exiting...')
            return
        }

        submitBtn.setAttribute('disabled', 'true');
        submitBtn.innerHTML = '<div class="small-loader"></div>'

        fetch('signup.php?ajax=2', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `username=${usernameElement.value}&email=${emailElement.value}&password=${passwordElement.value}`
        })
        .then(response => response.json())
        .then(data => {
            console.log('Received response:', data);
            if (data.id === 0) {
                error.innerHTML = 'User with this email address already exists.';
                submitBtn.removeAttribute('disabled');
                submitBtn.innerHTML = 'Sign Up'
            } else if (data.id === -1) {
                error.innerHTML = 'Hmmm... something went wrong.';
                submitBtn.removeAttribute('disabled');
                submitBtn.innerHTML = 'Sign Up'
            } else {
                window.location.href = 'index.php';
            }
        })
        
    })
})

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}