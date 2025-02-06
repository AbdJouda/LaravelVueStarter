<template>
    <div class="p-6 bg-white shadow-lg rounded-lg">
        <!-- Card Header -->
        <div class="flex justify-between items-center border-b pb-4 mb-4">
            <h2 class="text-xl font-semibold text-gray-800">My Todo List</h2>
            <el-button type="primary" size="small" @click="openAddTodoModal">Add Todo</el-button>
        </div>

        <!-- Todo List -->
        <div v-if="todos.length > 0" class="space-y-4">
            <div
                v-for="(todo, index) in todos"
                :key="todo.id"
                class="flex justify-between items-center p-4 bg-gray-50 rounded-md shadow-sm"
            >
                <div>
                    <h3 class="text-lg font-semibold" :class="{'line-through text-gray-500': todo.is_completed}">{{ todo.title }}</h3>
                    <p class="text-sm text-gray-600" v-if="todo.description">{{ todo.description }}</p>
                </div>

                <div class="flex items-center space-x-3">
                    <!-- Priority Badge -->
                    <span
                        :class="{
              'bg-red-500': todo.priority === 'high',
              'bg-yellow-400': todo.priority === 'medium',
              'bg-green-500': todo.priority === 'low',
            }"
                        class="px-2 py-1 text-white rounded-full text-xs"
                    >
            {{ todo.priority }}
          </span>

                    <!-- Mark as Completed Button -->
                    <el-button
                        v-if="!todo.is_completed"
                        size="small"
                        type="success"
                        @click="toggleCompletion(todo)"
                    >
                        Complete
                    </el-button>

                    <!-- Delete Todo Button -->
                    <el-button
                        size="small"
                        type="danger"
                        icon="el-icon-delete"
                        @click="deleteTodo(todo.id)"
                    />
                </div>
            </div>
        </div>

        <div v-else class="text-center text-gray-500">
            No todos available. Add a new todo to get started!
        </div>
    </div>
</template>

<script setup>
const  todos = ref([
        { id: 1, title: 'Finish project documentation', description: 'Complete the final draft for the project docs.', is_completed: false, priority: 'high' },
        { id: 2, title: 'Buy groceries', is_completed: true, priority: 'medium' },
        { id: 3, title: 'Workout', description: 'Run 5km and do strength training.', is_completed: false, priority: 'low' },
    ]) ;
function toggleCompletion(todo) {
    todo.is_completed = !todo.is_completed;
}

function deleteTodo(id) {
    this.todos = this.todos.filter(todo => todo.id !== id);
}
function openAddTodoModal() {
    // Open a modal or redirect to add todo form (you can implement this feature)
    console.log('Open Add Todo Modal');
}
</script>

<style scoped>
/* Custom styles for additional tweaks */
</style>
