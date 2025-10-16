import { getAuthOptions } from "./auth/checkAuth";

async function getAndMakeTodos(){
    const options = getAuthOptions('GET');
    if (!options) return; // Stop if we can't get auth options

    const url = "http://localhost:8080/api/getTodos.php";
    let response  = await fetch(url, options);
    if(response.status === 401){
        console.error("Authentication failed. Token is invalid or expired.");
        // Clear old token and redirect
        localStorage.removeItem('authToken');
        window.location.href = '/login.html';
        return;
    }
    let txt = await response.text();
    try{
        let todosJson = JSON.parse(txt);
        let listEl = document.querySelector("#todoList");
        listEl.innerHTML = "";
        todosJson.forEach(todoData => {
            listEl.appendChild(makeTodo(todoData));
        });
    } catch{
        console.warn(txt);
    }
    
    
}
function makeTodo(data){
    let htmlEl = document.createElement("li");
    htmlEl.addEventListener('click', async (e)=>{
        let id = data.id;
        let status = data.status=="done" ? "todo": "done";
        const url = "http://localhost:8080/api/edittodo.php";

        const options = getAuthOptions('PATCH');
        if (!options) return;
        options.body = JSON.stringify({
            id: id,
            status: status
        });

        fetch(url, options)
        .then(response => {
            if (response.status === 401) {
                console.error("Authentication failed for update.");
                localStorage.removeItem('authToken');
                window.location.href = '/login.html';
            } else if (!response.ok) {
                // Handle other server errors
                console.error("Failed to update todo status:", response.statusText);
            } else {
                // Refresh the todo list on success
                setTodos();
            }
        })
        .catch(err => console.error("Network error during todo update:", err));
    });
    htmlEl.className = "todo";
    htmlEl.classList.add(data.status);
    htmlEl.innerText = data.text;
    return htmlEl;
}
export function setTodos() {
    getAndMakeTodos().catch(error => console.error("SET TODOS critical error:", error));
}

