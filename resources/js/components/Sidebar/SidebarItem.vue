<script setup>
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useSidebarStore } from '@/stores/sidebarStore'
import SidebarDropdown from './SidebarDropdown.vue'

const props = defineProps({
    item: { type: Object, required: true },
    index: { type: Number, required: true }
})

const sidebarStore = useSidebarStore()
const router = useRouter()
const route = useRoute()

const isActiveRoute = computed(() => {
    if (props.item.children && props.item.children.length) {
        return props.item.children.some(child => child.name === route.name)
    }
    return props.item.href === route.path
})

const handleItemClick = () => {
    if (props.item.children && props.item.children.length) {
        sidebarStore.page = sidebarStore.page === props.item.title ? '' : props.item.title
    } else if (props.item.href) {
        router.push(props.item.href)
    }
}
</script>

<template>
    <li>
        <a
            :class="{ 'bg-graydark dark:bg-meta-4': isActiveRoute }"
            class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
            href="#"
            @click.prevent="handleItemClick">
            <font-awesome-icon :icon="item.icon" />
            {{ item.title }}
            <svg
                v-if="item.children"
                :class="{ 'rotate-180': sidebarStore.page === item.title }"
                class="absolute right-4 top-1/2 -translate-y-1/2 fill-current transition-transform duration-300"
                fill="none"
                height="20"
                viewBox="0 0 20 20"
                width="20"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    clip-rule="evenodd"
                    d="M4.41107 6.9107C4.73651 6.58527 5.26414 6.58527 5.58958 6.9107L10.0003 11.3214L14.4111 6.91071C14.7365 6.58527 15.2641 6.58527 15.5896 6.91071C15.915 7.23614 15.915 7.76378 15.5896 8.08922L10.5896 13.0892C10.2641 13.4147 9.73651 13.4147 9.41107 13.0892L4.41107 8.08922C4.08563 7.76378 4.08563 7.23614 4.41107 6.9107Z"
                    fill="currentColor"
                    fill-rule="evenodd"
                />
            </svg>
        </a>

        <div
            v-if="item.children && item.children.length"
            v-show="sidebarStore.page === item.title"
            class="overflow-hidden transition-all duration-500 ease-out">
            <SidebarDropdown
                v-hasPermission="item.permissions"
                :items="item.children"
            />
        </div>
    </li>
</template>
