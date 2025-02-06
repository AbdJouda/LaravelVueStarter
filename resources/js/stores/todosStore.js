import {TodoService} from '@/services/apiService';
import {handleError} from '@/utils';

const useTodosStore = defineStore('todos', () => {

    const todos = ref([]);
    const currentTodo = ref(null);

    async function fetchTodos(params = {}) {
        try {
            const {data} = await TodoService.getTodos(params);
            todos.value = data;
        } catch (error) {
            handleError(error);
        }
    }

    async function createTodo(payload) {
        try {

            const res = await TodoService.createTodo(payload);

            todos.value.unshift(res.data);

            return {res};

        } catch (error) {
            return {error: error};
        }
    }

    async function updateTodo(todoId, payload) {
        try {

            const res = await TodoService.updateTodo(todoId, payload);

            const index = todos.value.findIndex(todo => todo.id === todoId);
            if (index !== -1) {
                todos.value[index] = { ...todos.value[index], ...res.data };
            }

            return {res};

        } catch (error) {
            return {error: error};
        }
    }

    async function deleteTodo(todoId) {
        try {

            const res = await TodoService.deleteTodo(todoId);
            todos.value = todos.value.filter(todo => todo.id !== todoId);
            return {res};

        } catch (error) {
            return {error: error};
        }
    }

    async function toggleCompleteStatus(todoId) {
        try {

            const res = await TodoService.toggleCompleteStatus(todoId);

            const todo = this.todos.find(t => t.id === todoId);

            if (todo) todo.is_completed = !todo.is_completed;

            return {res};

        } catch (error) {
            return {error: error};
        }
    }


    function setCurrentTodo(todo) {
        currentTodo.value = todo;
    }

    function resetCurrentTodo() {
        currentTodo.value = null;
    }


    return {
        fetchTodos,
        createTodo,
        updateTodo,
        deleteTodo,
        toggleCompleteStatus,
        setCurrentTodo,
        resetCurrentTodo,
        todos,
        currentTodo,
    };
});

export {useTodosStore};
