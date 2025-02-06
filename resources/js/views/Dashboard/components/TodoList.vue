<script setup>
import { useTodosStore } from '@/stores/todosStore';
import TodoItem from "@/views/Todo/components/TodoItem.vue";
import {RouteNames} from '@/constants/routeNames'

const router = useRouter();
const todoStore = useTodosStore();
const {todos} = storeToRefs(todoStore);

onMounted(async () => {
    await todoStore.fetchUpcomingTodos();
});
</script>
<template>
    <el-card class="shadow-lg rounded-lg">
        <!-- Card Header -->
        <div class="flex justify-between items-center border-b pb-4 mb-4">
            <h3 class="font-semibold">Upcoming Tasks</h3>
            <el-button type="primary" size="small" @click="router.push({ name: RouteNames.TODO_LIST })">View All</el-button>
        </div>

        <!-- Todo List -->
        <div  class="space-y-4">
            <div v-if="todos?.length === 0"
                 class="flex items-center dark:bg-gray-800 dark:border-gray-700 justify-between p-4 bg-white rounded-lg shadow-sm border border-gray-200">
                No todos available. Add a new todo to get started!
            </div>
            <TodoItem v-else v-for="(item, index) in todos"
                      :key="index"
                      :item="item" />
        </div>

    </el-card>
</template>

<style scoped>
</style>
