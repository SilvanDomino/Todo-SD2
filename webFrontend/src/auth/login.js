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
        credentials: 'include',
        body: JSON.stringify({ username: username, password: password })
    }
    try{
        const response = await fetch(url, options);
        if (response.ok) {
            const result = await response.json();
            console.log(result);
            localStorage.setItem('authToken', result.token);
            messageEl.textContent = 'Login successful, redirecting...';
            messageEl.style.color = 'green';
            setTimeout(() => { window.location.href = '/index.html'; }, 1000)  
        } else {
            const errorResult = await response.json(); 
            messageEl.textContent = errorResult.error || 'Login failed. Check credentials.';
            messageEl.style.color = 'red';
        }
    } catch (error) {
        console.error('Fetch error:', error);
        // Handle cases where the network fails or JSON parsing fails
        messageEl.textContent = 'Network error or connection failed. See console for details.';
        messageEl.style.color = 'red';
    }
})