<script setup>
import { useGlobalStore } from '@/stores/globalStore';

const globalStore = useGlobalStore();
const loading = computed(() => globalStore.loading)

</script>
<template>
    <div class="h-screen flex flex-col sm:flex-row bg-slate-20 w-full">
        <div class="w-full">
            <div class="main-content flex flex-col flex-1 w-full space-y-6">
                <div class="w-full h-38 relative bg-transparent border-none">
                    <div class="py-1.25 px-4 md:px-7.5 lg:px-6.2">
                        <div class="py-2 flex flex-col justify-center sm:py-6">
                            <div class="relative py-3 sm:max-w-xl sm:mx-auto">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-blue-300 to-blue-600 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl">
                                </div>
                                <div class="relative px-4 py-10 bg-white shadow-lg sm:rounded-3xl sm:p-20"  v-loading.lock="loading">
                                    <router-view v-slot="{ Component }">
                                        <component :is="Component"/>
                                    </router-view>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

