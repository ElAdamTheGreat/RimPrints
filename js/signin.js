document.addEventListener('DOMContentLoaded', function() {
    const usermailElement = document.getElementById('usermail');
    const passwordElement = document.getElementById('password');
    const errorUsermail = document.getElementById('error-usermail');
    const errorPassword = document.getElementById('error-password');

    usermailElement.addEventListener('input', function() {
        usermailElement.style.border = '1px solid #616c7a'
        errorUsermail.innerHTML = ''
    })

    passwordElement.addEventListener('input', function() {
        passwordElement.style.border = '1px solid #616c7a'
        errorPassword.innerHTML = ''
    })

    document.getElementById('registration-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const usermail = usermailElement.value;
        const password = passwordElement.value;
        const status = document.getElementById('status')

        // Did user enter username or email? 
        let email, username
        if (usermail.includes("@")) {
            email = usermail;
        } else {
            username = usermail;
        }

        // Client-side validation
        if (username !== undefined && (username.length < 4 || username.length > 16)) {
            errorUsermail.innerHTML = "Username length must be between 4 and 16 characters.";
            usermailElement.style.border = '2px solid red'
        }
        if (!validateEmail(email) && email !== undefined) {
            errorUsermail.innerHTML = "Please enter a valid email address.";
            usermailElement.style.border = '2px solid red'
        }
        if (password.length < 4) {
            errorPassword.innerHTML = "Please enter a password with valid length.";
            passwordElement.style.border = '2px solid red'
        }

        if (errorUsermail.innerHTML || errorPassword.innerHTML) {
            return
        }

        status.innerHTML = '<div class="small-loader"></div>';
        
        // Perform AJAX validation
        console.log('Sending AJAX request for validation...');
        fetch(`signin.php?ajax=1`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `usermail=${usermail}&password=${password}`
        })
        .then(response => response.json())
        .then(data => {
            console.log('Received response:', data);
            if (data.success) {
                //display loading wheel
                const content = document.getElementById('content')
                // === 1.1 display "Signing you in..." screen in the meantime
                content.innerHTML = '<div class="col"><div class="loader"></div><h3 class="low-key">Signing you in...</h3></div>';

                // If validation passes, fetch user details
                console.log('Fetching user details...');
                fetch(`signin.php?ajax=2`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `usermail=${usermail}`
                    // this?
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Received user details:', data);
                    if (data.success) {
                        // Redirect to the previous page
                        window.location.href = document.referrer || 'index.php';
                    } else {
                        content.textContent = data.error;
                    }
                });
            } else {
                errorElement.textContent = data.error;
                status.innerHTML = '<button type="submit" id="submit" class="btn-sm">Sign In</button>';
            }
        });
    });
})

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}