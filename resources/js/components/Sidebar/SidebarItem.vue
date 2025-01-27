<script setup>
import {useSidebarStore} from '@/stores/sidebarStore';
import SidebarDropdown from './SidebarDropdown.vue'

const sidebarStore = useSidebarStore();
const router = useRouter();

const props = defineProps(['item', 'index'])
const currentPage = useRoute().name
const route = useRoute();

const isActiveRoute = () => {
    return props.item.route === route.name ||
        route.matched[0]?.children.some((matchedRoute) => matchedRoute.name === props.item.route)
}
const handleItemClick = () => {
    sidebarStore.page = sidebarStore.page === props.item.label ? '' : props.item.label

    if (props.item.children) {
        return props.item.children.some((child) => sidebarStore.selected === child.label)
    }
    props.item.route
        ? router.push({name: props.item.route})
        : '';

}
</script>

<template>
    <li>
        <a
            :class="{
        'bg-graydark dark:bg-meta-4': isActiveRoute()
      }"
            class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
            href="javascript:void(0)"
            @click.prevent="handleItemClick()">
            <font-awesome-icon :icon="item.icon"/>

            {{ item.label }}

            <svg
                v-if="item.children"
                :class="{ 'rotate-180': sidebarStore.page === item.label }"
                class="absolute right-4 top-1/2 -translate-y-1/2 fill-current"
                fill="none"
                height="20"
                viewBox="0 0 20 20"
                width="20"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    clip-rule="evenodd"
                    d="M4.41107 6.9107C4.73651 6.58527 5.26414 6.58527 5.58958 6.9107L10.0003 11.3214L14.4111 6.91071C14.7365 6.58527 15.2641 6.58527 15.5896 6.91071C15.915 7.23614 15.915 7.76378 15.5896 8.08922L10.5896 13.0892C10.2641 13.4147 9.73651 13.4147 9.41107 13.0892L4.41107 8.08922C4.08563 7.76378 4.08563 7.23614 4.41107 6.9107Z"
                    fill=""
                    fill-rule="evenodd"
                />
            </svg>
        </a>

        <!-- Dropdown Menu Start -->
        <div v-show="sidebarStore.page === item.label"
             class="translate transform transition-all transition-[100px] duration-500 ease-out overflow-hidden">
            <SidebarDropdown
                v-if="item.children"
                v-hasPermission="item.permissions"
                :currentPage="currentPage"
                :items="item.children"
                :page="item.label"

            />
            <!-- Dropdown Menu End -->
        </div>
    </li>
</template>
