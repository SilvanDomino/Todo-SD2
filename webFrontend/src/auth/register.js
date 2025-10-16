let form = document.getElementById('register-form');
form.addEventListener('submit', async(e)=>{
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
    try{
        const response = await fetch(url, options);
        const result = await response.text();
        messageEl.textContent = result;
        console.log(response);
        if(response.status == 200){
            messageEl.style.color = 'green';
            setTimeout(() => { window.location.href = '/login.html'; }, 25000)
        } else {
            // Failure: Show error message
            messageEl.style.color = 'red';
        }
    } catch (error) {
        messageEl.textContent = 'Network error or connection failed.';
        messageEl.style.color = 'red';
    }
})