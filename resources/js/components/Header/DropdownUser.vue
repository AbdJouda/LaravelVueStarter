<script setup>
import { useAuthStore } from '@/stores/authStore';
import {RouteNames} from '@/constants/routeNames'
import { useSidebarStore } from '@/stores/sidebarStore';

const router = useRouter();
const authStore = useAuthStore();
const target = ref(null)
const dropdownOpen = ref(false)
const sidebarStore = useSidebarStore();


onClickOutside(target, () => {
  dropdownOpen.value = false
})

async function onLogout() {
    await authStore.logout();
    await router.push({name: RouteNames.LOGIN});
}
</script>

<template>
  <div class="relative" ref="target">
    <a
      class="flex items-center gap-4"
      href="javascript::void(0)"
      @click.prevent="dropdownOpen = !dropdownOpen"
    >
      <span class="hidden text-right lg:block">
        <span class="block text-sm font-medium text-black dark:text-white">{{ authStore.user?.name }}</span>
      </span>

      <span class="h-12 w-12 rounded-full">
      <el-avatar :size="40" :src="authStore.user?.profile_photo_url" style="margin-right: 10px;"/>
      </span>

      <svg
        :class="dropdownOpen && 'rotate-180'"
        class="hidden fill-current sm:block"
        width="12"
        height="8"
        viewBox="0 0 12 8"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          fill-rule="evenodd"
          clip-rule="evenodd"
          d="M0.410765 0.910734C0.736202 0.585297 1.26384 0.585297 1.58928 0.910734L6.00002 5.32148L10.4108 0.910734C10.7362 0.585297 11.2638 0.585297 11.5893 0.910734C11.9147 1.23617 11.9147 1.76381 11.5893 2.08924L6.58928 7.08924C6.26384 7.41468 5.7362 7.41468 5.41077 7.08924L0.410765 2.08924C0.0853277 1.76381 0.0853277 1.23617 0.410765 0.910734Z"
          fill=""
        />
      </svg>
    </a>

    <!-- Dropdown Start -->
    <div
      v-show="dropdownOpen"
      class="absolute right-0 mt-4 flex w-62.5 flex-col rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
    >
      <ul class="flex flex-col gap-5 border-b border-stroke px-6 py-7.5 dark:border-strokedark">
        <li>
          <router-link
            :to="{name: RouteNames.PROFILE}"
            class="flex items-center gap-3.5 text-sm font-medium duration-300 ease-in-out hover:text-primary dark:hover:text-white lg:text-base"
          >
              <font-awesome-icon :icon="['fas', 'user']"/>
              My Profile
          </router-link>
        </li>
      </ul>
      <button
        class="flex items-center gap-3.5 py-4 px-6 text-sm font-medium duration-300 ease-in-out hover:text-primary dark:hover:text-white lg:text-base"
        @click="onLogout"
      >
          <font-awesome-icon :icon="['fas', 'sign-out-alt']"/>
          Log Out
      </button>
    </div>
    <!-- Dropdown End -->
  </div>
</template>
