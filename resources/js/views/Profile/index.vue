<script setup>
import Password from './components/Password.vue';
import Info from "./components/Info.vue";
import DeleteAccount from "./components/DeleteAccount.vue";

const scrollToSection = (id) => {
    const section = document.getElementById(id);
    if (section) {
        section.style.scrollMarginTop = '100px';
        section.scrollIntoView({ behavior: 'smooth', block: 'start' });

    }
};


</script>
<template>
    <div class="grid md:grid-cols-[15rem_1fr] grid-cols-1 gap-4">
        <div class="row-span-3">
            <aside class="fixed hidden md:block rounded-lg menu w-60">
                <el-card class="dark:bg-gray-800 dark:border-gray-700 px-0" shadow="hover">
                    <nav>
                        <ul class="space-y-2">
                            <li
                                class="px-6 py-3 flex items-center gap-3 hover:bg-gray-100 dark:hover:bg-sky-500 cursor-pointer"
                                @click="scrollToSection('basic-info')"
                            >
                                <font-awesome-icon :icon="['fas', 'user']"/>
                                <span>Basic Info</span>
                            </li>
                            <li
                                class="px-6 py-3 flex items-center gap-3 hover:bg-gray-100 dark:hover:bg-sky-500 cursor-pointer"
                                @click="scrollToSection('change-password')"
                            >
                                <font-awesome-icon :icon="['fas', 'lock']"/>
                                <span>Change Password</span>
                            </li>
                            <li
                                class="px-6 py-3 flex items-center gap-3 text-red-500 hover:bg-red-50 dark:hover:bg-red-500 dark:text-white cursor-pointer"
                                @click="scrollToSection('delete-account')"
                            >
                                <font-awesome-icon :icon="['fas', 'trash']"/>
                                <span>Delete Account</span>
                            </li>
                        </ul>
                    </nav>
                </el-card>
            </aside>
        </div>
        <Info />
        <Password />
        <DeleteAccount />
    </div>
    <div>
    </div>

</template>
<style scoped>
.menu :deep(.el-card__body){
    padding: 0  !important;
}
</style>
