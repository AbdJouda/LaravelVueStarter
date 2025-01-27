<script setup>
import { useGlobalStore } from '@/stores/globalStore';

const globalStore = useGlobalStore();
const loading = computed(() => globalStore.loading)
</script>

<template>
    <!-- ===== Page Wrapper Start ===== -->
    <div class="flex h-screen overflow-hidden">
        <!-- ===== Sidebar Start ===== -->
        <SidebarArea />
        <!-- ===== Sidebar End ===== -->

        <!-- ===== Content Area Start ===== -->
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            <!-- ===== Header Start ===== -->
            <HeaderArea />
            <!-- ===== Header End ===== -->

            <!-- ===== Main Content Start ===== -->
            <main>
                <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10"  v-loading.lock="loading">
                        <BreadcrumbDefault />

                        <router-view v-slot="{ Component }">
                            <component :is="Component"/>
                        </router-view>
                </div>
            </main>
            <!-- ===== Main Content End ===== -->
        </div>
    </div>
    <!-- ===== Page Wrapper End ===== -->
</template>
