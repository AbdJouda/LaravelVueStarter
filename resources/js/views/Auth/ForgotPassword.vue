<script setup>
import { RouteNames } from '@/constants/routeNames'
import ForgotPasswordForm from './components/ForgotPasswordForm.vue';

const router = useRouter();

</script>

<template>
        <div class="max-w-md mx-auto">
            <div>
                <h1 class="text-2xl font-semibold">Forgot Password</h1>
            </div>
            <ForgotPasswordForm />
        </div>

        <div class="w-full flex justify-center">
            <el-link type="primary" @click="router.push({ name: RouteNames.LOGIN, replace: true })">
                Back to Login
            </el-link>
        </div>
</template>

<style scoped>

</style>

