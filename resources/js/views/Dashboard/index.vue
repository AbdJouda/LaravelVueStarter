<template>
    <div class="w-full mx-auto">
        <div class="w-full">
        </div>
        <div class="mt-6">
            <div class="flex flex-wrap">
                <div class="w-2/4 ">
                    <TodoList />
                </div>
                <div class="lg:flex-4 lg:max-w-1/3 w-full lg:pl-3.5">

                </div>
            </div>
        </div>

        <div class="mt-6">
            <div class="flex flex-wrap">
                <div class="lg:flex-8 lg:max-w-2/3 w-full lg:mb-0 lg:pr-3.5">
                </div>
                <div class="lg:flex-4 lg:max-w-1/3 w-full lg:pl-3.5">
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import TodoList from './components/TodoList.vue';

</script>

<style scoped>

</style>
