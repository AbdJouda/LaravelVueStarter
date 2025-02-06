<script setup>
import { useTodosStore } from '@/stores/todosStore';
import { handleError } from "@/utils";

const props = defineProps({
    item: Object,
});

const todoStore = useTodosStore();

const emit = defineEmits(["edit", "delete"]);

const getPriorityTagType = (priority) => {
    switch (priority) {
        case "High": return "danger";
        case "Medium": return "warning";
        case "Low": return "info";
        default: return "info";
    }
};

const toggleCompletion = async () => {

    const { res, error } = await todoStore.toggleCompleteStatus(props.item.id);

    if (error) {
        handleError(error);
        return;
    }

    ElMessage.success({
        message: res?.message,
    });

};

const confirmDelete = async () => {
        await ElMessageBox.confirm("Are you sure you want to delete this task?", "Warning", {
            confirmButtonText: "Delete",
            cancelButtonText: "Cancel",
            type: "warning",
        });
        const { res, error } = await todoStore.deleteTodo(props.item.id);

        if (error) {
            handleError(error);
            return;
        }

    ElMessage.success({
        message: res?.message,
    });
}
const handleEdit = () => {
    todoStore.setCurrentTodo(props.item);
};

</script>

<template>
    <div :class="{ 'line-through': item.is_completed, 'font-semibold': true }"
         class="flex items-center dark:bg-gray-800 dark:border-gray-700 justify-between p-4 bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center space-x-4">
            <el-checkbox v-model="item.is_completed" @change="toggleCompletion" class="rounded" />
            <div class="flex flex-col">
                <span>
                    {{ item.title }}
                    <el-tag v-if="item.is_completed" type="success" effect="dark" disable-transitions class="ml-2">
                        Completed
                    </el-tag>
                    <el-tag v-else :type="getPriorityTagType(item.priority)" effect="dark" disable-transitions class="ml-2">
                        {{ item.priority }}
                    </el-tag>

                </span>
                <span class="text-sm text-gray-500" v-if="item.description">{{ item.description }}</span>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <span v-if="item.due_date" class="text-sm">Due: {{ item.due_date.uk_string }}</span>
            <el-tooltip content="Edit"
                        placement="bottom" >
                <font-awesome-icon :icon="['fas', 'pen-to-square']"
                                   class="fa-lg text-blue-500 cursor-pointer"
                                   @click="handleEdit"/>
            </el-tooltip>
            <el-tooltip content="Delete"
                        placement="bottom">
                <font-awesome-icon
                    :icon="['fas', 'trash']"
                    class="cursor-pointer text-red-500 fa-lg"
                    @click="confirmDelete"/>
            </el-tooltip>
        </div>
    </div>
</template>
