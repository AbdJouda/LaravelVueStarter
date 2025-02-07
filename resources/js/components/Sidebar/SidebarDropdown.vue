<script setup>
import { useRoute } from 'vue-router'

const props = defineProps({
    items: { type: Array, required: true },
})

const route = useRoute()

const isActiveRoute = (child) => {
    return child.name === route.name
}

</script>

<template>
    <ul class="mt-4 mb-5.5 flex flex-col gap-2.5 pl-6">
        <li v-for="(childItem, index) in items" :key="index">
            <router-link
                v-has-permission="childItem.meta.permissions"
                :to="{ name: childItem.name }"
                class="group relative flex items-center gap-2.5 rounded-md px-4 font-medium text-bodydark2 duration-300 ease-in-out hover:text-white"
                :class="{ '!text-white': isActiveRoute(childItem) }"
            >
                <font-awesome-icon :icon="childItem.meta.icon" />
                {{ childItem.meta.title }}
            </router-link>
        </li>
    </ul>
</template>
