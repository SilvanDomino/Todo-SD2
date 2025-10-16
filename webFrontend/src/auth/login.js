let form = document.getElementById('register-form');
form.addEventListener('submit', async(e)=>{
    e.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const messageEl = document.getElementById('message');
    const url = "http://localhost:8080/auth/login.php";

    const options = {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password })
    }
    try{
        const response = await fetch(url, options);
        const result = await response.text();
        if(response.status == 200){
            messageEl.textContent = 'Login successful, redirecting...';
            messageEl.style.color = 'green';
            setTimeout(() => { window.location.href = '/index.html'; }, 2500)
        } else {
            const result = await response.json();
                messageEl.textContent = result.message || 'Login failed. Check credentials.';
                messageEl.style.color = 'red';
            messageEl.style.color = 'red';
        }
    } catch (error) {
        messageEl.textContent = 'Network error or connection failed.';
        messageEl.style.color = 'red';
    }
})