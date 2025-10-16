import { setTodos } from "./setTodos";
import { addTodo } from "./addTodo";


setTodos();

let button = document.querySelector("#todo-add");
button.addEventListener("click", addTodo);
