<script setup>
import {RouteNames} from '@/constants/routeNames'
import ResetPasswordForm from './components/ResetPasswordForm.vue';
import VerifyResetCodeForm from './components/VerifyResetCodeForm.vue';

const router = useRouter();
const route = useRoute();

const hasVerifiedCode = ref(false);
const resetParams = ref(null);

if (route.query?.email && route.query?.code) {
    resetParams.value = {
        email: route.query?.email,
        code: route.query?.code,
    };
}

const handleVerified = () => {
    hasVerifiedCode.value = true;
};

</script>

<template>
        <div class="max-w-md mx-auto">
            <div>
                <h1 class="text-2xl font-semibold">{{hasVerifiedCode ? 'Reset Your Password' : 'Verify Your Reset Code'}}</h1>
            </div>
            <el-collapse-transition>
                <div v-if="!hasVerifiedCode">
                    <VerifyResetCodeForm
                        :code="resetParams.code"
                        :username="resetParams.email"
                        @verified="handleVerified"
                    />
                </div>
                <div v-else>
                    <ResetPasswordForm
                        :code="resetParams.code"
                        :username="resetParams.email"
                    />
                </div>
            </el-collapse-transition>
        </div>

        <div class="w-full flex justify-center">
            <el-link type="primary" @click="router.push({ name: RouteNames.LOGIN, replace: true })">
                Back to Login
            </el-link>
        </div>
</template>

<style>

</style>
