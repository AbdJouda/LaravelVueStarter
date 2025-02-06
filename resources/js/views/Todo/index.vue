<script setup>
import { useTodosStore } from '@/stores/todosStore';
import TodoItem from "./components/TodoItem.vue";
import { debounce } from "lodash";

const todoStore = useTodosStore();
const {todos} = storeToRefs(todoStore);
const searchText = ref();
const filterPriority = ref("");
const filterCompleted = ref(null);
const sortBy = ref("due_date:asc");

const queryParams = ref({});

onMounted(async () => {
    await todoStore.fetchTodos();
});

const updateQueryParams = (key, value) => {
    queryParams.value = { ...queryParams.value, [key]: value };
};

const debouncedSearch = debounce((value) => {
    updateQueryParams("search", value);
}, 300);

watch(queryParams, () => {
    todoStore.fetchTodos(queryParams.value);
}, { deep: true });

const updateSortParams = () => {
    const [field, order] = sortBy.value.split(":");
    updateQueryParams("sort_by", field);
    updateQueryParams("sort_dir", order);
};
watch(sortBy, updateSortParams);

const getPriorityTagType = (priority) => {
    switch (priority) {
        case "High": return "danger";
        case "Medium": return "warning";
        case "Low": return "success";
        default: return "info";
    }
};


</script>

<template>
    <div class="space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <!-- Search Input (Left Aligned) -->
            <div class="w-full md:w-1/4">
                <el-input
                    v-model="searchText"
                    @update:modelValue="debouncedSearch"
                    placeholder="Search tasks..."
                    clearable
                    class="w-full"
                    prefix-icon="el-icon-search"
                />
            </div>

            <!-- Filters & Sorting (Right Aligned) -->
            <div class="flex space-x-4 w-full md:w-auto">
                <!-- Priority & Status Filter -->
                <!-- Priority Filter -->
                <div class="w-full md:w-40">
                    <el-select v-model="filterPriority" placeholder="Priority" @update:modelValue="updateQueryParams('priority', $event)" clearable class="w-full">
                        <el-option label="High" value="High" />
                        <el-option label="Medium" value="Medium" />
                        <el-option label="Low" value="Low" />
                    </el-select>
                </div>

                <!-- Status Filter -->
                <div class="w-full md:w-40">
                    <el-select v-model="filterCompleted" placeholder="Status" @update:modelValue="updateQueryParams('is_completed', $event)" clearable class="w-full">
                        <el-option label="Completed" :value="1" />
                        <el-option label="Not Completed" :value="0" />
                    </el-select>
                </div>


                <!-- Sorting Dropdown -->
                <div class="w-full md:w-45">
                    <el-select v-model="sortBy" placeholder="Sort by" class="w-40">
                        <el-option label="⬆️ Due Date (Asc)" value="due_date:asc" />
                        <el-option label="⬇️ Due Date (Desc)" value="due_date:desc" />
                        <el-option label="⬆️ Priority (Asc)" value="priority:asc" />
                        <el-option label="⬇️ Priority (Desc)" value="priority:desc" />
                        <el-option label="⬆️ Status (Asc)" value="is_completed:asc" />
                        <el-option label="⬇️ Status (Desc)" value="is_completed:desc" />
                    </el-select>
                </div>
            </div>
        </div>
        <div v-if="todos.length === 0"
             class="flex items-center dark:bg-gray-800 dark:border-gray-700 justify-between p-4 bg-white rounded-lg shadow-sm border border-gray-200">
            No tasks found.
        </div>
        <TodoItem v-else v-for="(item, index) in todos"
                  :key="index"
                  :item="item" />

    </div>
</template>
