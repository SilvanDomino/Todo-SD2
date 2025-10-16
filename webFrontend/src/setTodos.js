
async function getAndMakeTodos(){
    let url = "http://localhost:8080/api/getTodos.php";
    let response  = await fetch(url);
    let todosJson = await response.json();
    let listEl = document.querySelector("#todoList");
    listEl.innerHTML = "";
    todosJson.forEach(todoData => {
        listEl.appendChild(makeTodo(todoData));
    });
}
function makeTodo(data){
    let htmlEl = document.createElement("li");
    htmlEl.addEventListener('click', async (e)=>{
        let id = data.id;
        let status = data.status=="done" ? "todo": "done";
        let url = "http://localhost:8080/api/edittodo.php";
        let options = {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json" 
            },
            body: JSON.stringify({
                id:id,
                status: status
            })
        }
        fetch(url, options).then(response=>setTodos())
        .catch(err=>console.log(err));
    });
    htmlEl.className = "todo";
    htmlEl.classList.add(data.status);
    htmlEl.innerText = data.text;
    return htmlEl;
}
export function setTodos(){{
    getAndMakeTodos().catch(error=>console.error(error));
}}

