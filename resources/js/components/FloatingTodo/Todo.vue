<script setup>
import { useTodosStore } from '@/stores/todosStore';
import {useCreateForm} from '@/hooks/useCreateForm';
import validationSchemas from '@/utils/validationSchemas';
import {handleError} from '@/utils';

const todoStore = useTodosStore();
const isOpen = ref(false);
const priorities = ["Low", "Medium", "High"];

const initialFormValues = {
    title: "",
    description: "",
    due_date: null,
    priority: "Medium"
};

const todoForm = useCreateForm({
    initialValues: initialFormValues,
    schema: validationSchemas.todoSchema,
});

const {
    form: {errors, values, submitForm, resetForm, meta, setErrors, setFieldValue},
    fields,
    getErrorMessage,
} = todoForm;

const toggleWidget = () => {
    isOpen.value = !isOpen.value;
    if (!isOpen.value) {
        resetForm();
        todoStore.resetCurrentTodo();
    }
};

async function handleSubmit() {

    if (!meta.value.valid) {
        submitForm();
        return;
    }

    const method = todoStore.currentTodo
        ? todoStore.updateTodo(todoStore.currentTodo.id, values)
        : todoStore.createTodo(values)

    const {res, error} = await method;


    if (error) {
        handleError(error, setErrors);
        return;
    }

    ElMessage.success({
        message: res?.message,
    });

    todoStore.resetCurrentTodo();
    resetForm();

}

watch(() => todoStore.currentTodo, (todo) => {
    if (todo) {
        setFieldValue("title", todo.title);
        setFieldValue("description", todo.description);
        setFieldValue("due_date", todo.due_date?.iso_8601_format);
        setFieldValue("priority", todo.priority);
        isOpen.value = true;
    }
});


</script>
<template>
    <!-- Floating Button -->
    <div class="fixed bottom-6 right-6 z-9999">
        <el-button
            circle
            class="shadow-lg hover:cursor-pointer"
            size="large"
            type="primary"
            @click="toggleWidget"
        >
            <font-awesome-icon :icon="['fas', 'plus']"/>
        </el-button>

        <!-- Floating Widget -->
        <transition name="fade">
            <div
                v-if="isOpen"
                class="absolute bottom-16 right-0 w-80 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-4"
            >
                <div class="flex justify-between items-center border-b pb-2 mb-2">
                    <h3 class="text-lg font-bold">To-Do List</h3>
                    <font-awesome-icon :icon="['fas', 'close']" class="cursor-pointer" @click="toggleWidget"/>
                </div>
                <div class="flex flex-col gap-2 mb-4">
                    <el-form label-position="top" label-width="100px">
                        <el-form-item
                            :error="getErrorMessage('title')">
                            <el-input
                                v-model="fields.title.value.value"
                                clearable
                                placeholder="Title"
                                type="text"
                                @blur="fields.title.handleBlur"/>
                        </el-form-item>
                        <el-form-item
                            :error="getErrorMessage('description')">
                            <el-input
                                v-model="fields.description.value.value"
                                clearable
                                placeholder="Description"
                                type="textarea"
                                @blur="fields.description.handleBlur"/>
                        </el-form-item>
                        <el-form-item
                            :error="getErrorMessage('due_date')">
                            <el-date-picker
                                v-model="fields.due_date.value.value"
                                clearable
                                placeholder="Due Date"
                                type="date"
                                @blur="fields.due_date.handleBlur"/>
                        </el-form-item>
                        <el-form-item
                            :error="getErrorMessage('priority')">
                            <el-select
                                v-model="fields.priority.value.value"
                                placeholder="Priority"
                                @blur="fields.priority.handleBlur">
                                <el-option v-for="priority in priorities"
                                           :key="priority"
                                           :label="priority"
                                           :value="priority"/>
                            </el-select>
                        </el-form-item>
                        <el-button type="primary" @click="handleSubmit">
                            {{ todoStore.currentTodo ? 'Update Task' : 'Add Task' }}
                        </el-button>
                    </el-form>
                </div>
            </div>
        </transition>
    </div>
</template>
<style scoped>
/* Transition for fade effect */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s;
}

.fade-enter, .fade-leave-to {
    opacity: 0;
}
</style>
