let form = document.getElementById('register-form');
form.addEventListener('submit', async(e) => {
    e.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const messageEl = document.getElementById('message');
    const url = "http://localhost:8080/auth/register.php";

    const options = {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password })
    }

    try {
        const response = await fetch(url, options);
        // PHP returns plain text on success/failure, so we'll read it as text first
        const resultText = await response.text(); 
        
        if (response.ok) { // response.ok is true for status 200-299
            messageEl.textContent = resultText || 'Registration successful!';
            messageEl.style.color = 'green';
            setTimeout(() => {
                window.location.href = '/login.html';
            }, 2000) // Redirect after 2 seconds

        } else {
            // Failure: Show error message from the backend
            messageEl.textContent = resultText || 'Registration failed.';
            messageEl.style.color = 'red';
        }
    } catch (error) {
        console.error('Fetch error:', error);
        messageEl.textContent = 'Network error or connection failed.';
        messageEl.style.color = 'red';
    }
});