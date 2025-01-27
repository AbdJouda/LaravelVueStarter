<script setup>
import { useSidebarStore } from '@/stores/sidebarStore';
const sidebarStore = useSidebarStore();

const props = defineProps(['items', 'page'])
const items = ref(props.items)
const route = useRoute();

const handleItemClick = (index) => {
    const pageName =
        sidebarStore.selected === props.items[index].label ? '' : props.items[index].label
    sidebarStore.selected = pageName
}
const isActiveRoute = (index) => {
    return props.items[index].route === route.name ||
        route.matched[0]?.children.some((matchedRoute) => matchedRoute.name === props.items[0].route)
}
</script>

<template>
  <ul class="mt-4 mb-5.5 flex flex-col gap-2.5 pl-6">
    <template v-for="(childItem, index) in items" :key="index">
      <li>
        <router-link
          v-hasPermission="childItem.permissions"
          :to="{ name: childItem.route}"
          @click="handleItemClick(index)"
          href="javascript:void(0)"
          class="group relative flex items-center gap-2.5 rounded-md px-4 font-medium text-bodydark2 duration-300 ease-in-out hover:text-white"
          :class="{
            '!text-white': isActiveRoute(index)
          }"
        >
            <font-awesome-icon :icon="childItem.icon" />

            {{ childItem.label }}
        </router-link>
      </li>
    </template>
  </ul>
</template>
