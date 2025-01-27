<script setup>
import { ElConfigProvider } from 'element-plus'
import DefaultLayout from '@/layouts/default-layout.vue'
import AuthLayout from '@/layouts/auth-layout.vue'
import { useRoute } from 'vue-router'
import { computed } from 'vue'

const route = useRoute();
const isAuthLayout = computed(() => !route.meta?.requiresAuth);

</script>

<template>
    <el-config-provider :zIndex="9999">
        <AuthLayout v-if="isAuthLayout" />
        <DefaultLayout v-else />
    </el-config-provider>
</template>

<style>
#global-loading {
    z-index: 120000;
}
</style>
