<script setup>
import LoginForm from './components/LoginForm.vue';
import { useAuthStore } from '@/stores/authStore';
import {googleTokenLogin} from "vue3-google-login"
import {HFaceBookLogin} from '@healerlab/vue3-facebook-login';
import GoogleIcon from "../../components/Icons/GoogleIcon.vue";
import FacebookIcon from "../../components/Icons/FacebookIcon.vue";
import { RouteNames } from '@/constants/routeNames'

const authStore = useAuthStore();
const router = useRouter();

const facebookClientId = import.meta.env.VITE_FACEBOOK_CLIENT_ID

const googleLogin = async () => {
    try {
        const response = await googleTokenLogin();
        const token = response.access_token;
        await onSocialLogin('google', token);
    } catch (error) {
        console.error('Google login failed:', error);
        ElMessage.error('Google login failed. Please try again.');
    }
};

const onFbSuccess = (response) => {
    const token = response.authResponse.accessToken;
    onSocialLogin('facebook', token);
};

const onFbFailure = (response) => {
    console.error('Facebook login failed:', response);
    ElMessage.error('Facebook login failed. Please try again.');
};


const onSocialLogin = async (provider, code) => {
    const {error} = await authStore.socialLogin(provider, code);

    if (error) {
        ElMessage.error(error?.message || 'Login failed. Please try again.');
        return;
    }

    await router.push({ name: RouteNames.DASHBOARD });
};

</script>

<template>
        <div class="max-w-md mx-auto">
            <div>
                <h1 class="text-2xl font-semibold">Login</h1>
            </div>
            <LoginForm />
        </div>

        <div class="w-full flex justify-center">
            <el-link type="primary" @click="router.push({ name: RouteNames.FORGOT_PASSWORD })">
                Forget password?
            </el-link>
        </div>
        <div class="my-4 flex items-center gap-4">
            <hr class="w-full border-gray-300"/>
            <p class="text-sm text-gray-800 text-center">or</p>
            <hr class="w-full border-gray-300"/>
        </div>

        <div class="w-full flex justify-center">
            <div class="space-x-6 flex justify-center">
                <button class="border-none outline-none mt-1"
                        type="button"
                        @click="googleLogin">
                    <GoogleIcon/>
                </button>

                <HFaceBookLogin
                    v-slot="fbLogin"
                    :app-id="facebookClientId"
                    fields="email"
                    scope="email"
                    @onFailure="onFbFailure"
                    @onSuccess="onFbSuccess"
                >
                    <button class="border-none outline-none mt-2"
                            type="button"
                            @click="fbLogin.initFBLogin">
                        <FacebookIcon/>
                    </button>
                </HFaceBookLogin>

                <!--                        <button class="border-none outline-none"-->
                <!--                                type="button">-->
                <!--                           <AppleIcon />-->
                <!--                            <vue-apple-login></vue-apple-login>-->

                <!--                        </button>-->
            </div>
        </div>
</template>

<style scoped>

</style>

