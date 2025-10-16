import { setTodos } from "./setTodos";

export async function addTodo(){
    let textField = document.querySelector("#todo-input");
    let url = "http://localhost:8080/api/addtodo.php";
    let options = {
        method: "POST",
        headers: {
            "Content-Type": "application/json" 
        },
        body: JSON.stringify({text:textField.value})
    }
    try{
        let response = await fetch(url, options).catch(err=>{console.error(err)});
        if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
        let body = await response.text();
        console.log(body);
        setTodos();
    } catch(err){
        console.error(err);
    }
}