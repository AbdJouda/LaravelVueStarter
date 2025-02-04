<script setup>
import { useCreateForm } from '@/hooks/useCreateForm';
import validationSchemas from '@/utils/validationSchemas';
import {useAuthStore} from '@/stores/authStore';
import {getStorage, removeStorage, saveStorage} from '@/services/storageService';
import {RouteNames} from '@/constants/routeNames'
import { handleValidationError } from '@/utils';

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();

const rememberMe = ref(false);

const initialFormValues = {
    username: '',
    password: '',
};

const loginForm = useCreateForm({
    initialValues: initialFormValues,
    schema: validationSchemas.loginSchema,
});

const {
    form: { errors, values, submitForm, resetForm, meta, setErrors, setFieldValue,setTouched },
    fields,
    getErrorMessage,
} = loginForm;

onMounted(() => {
    const userInfo = getStorage('loginInfo');
    if (userInfo) {
        resetForm({ values: { ...userInfo } });
        setTouched({username: true, password: true});
        rememberMe.value = true;
    }
});

const onLogin = async () => {

    if (!meta.value.valid) {
        submitForm();
        return;
    }

    const { error } = await authStore.login(values.username, values.password);

    if (rememberMe.value) {
        saveStorage('loginInfo', {...values});
    } else {
        removeStorage('loginInfo');
    }

    if (error) {
        handleValidationError(error, setErrors);
        // Manually set meta.value.valid to true to avoid validation being marked as false
        // This is because calling setErrors might interfere with the form's validity state,
        // and we need to ensure the form stays valid for subsequent attempts after backend errors.
        nextTick(() => {
            meta.value.valid = true;
        });

        return;
    }

    await router.push({name: RouteNames.DASHBOARD});
}


</script>
<template>
    <div class="divide-y divide-gray-200">
        <div class="py-4 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
            <el-form class="space-y-6">
                <div class="relative">
                    <el-form-item
                        :error="getErrorMessage('username')"
                        class="el-form-item--label-top"
                        label="Enter your email or phone number">
                        <el-input
                            id="username"
                            v-model="fields.username.value.value"
                            autocomplete="off"
                            name="username"
                            placeholder="Email or phone number"
                            type="text"
                            @blur="fields.username.handleBlur">
                            <template #prefix>
                                <font-awesome-icon :icon="['fas', 'user']"/>
                            </template>
                        </el-input>
                    </el-form-item>
                </div>
                <el-form-item
                    :error="getErrorMessage('password')"
                    class="el-form-item--label-top"
                    label="Password">
                    <el-input
                        v-model="fields.password.value.value"
                        placeholder="Password"
                        show-password
                        type="password"
                        @blur="fields.password.handleBlur">
                        <template #prefix>
                            <font-awesome-icon :icon="['fas', 'lock']"/>
                        </template>
                    </el-input>
                </el-form-item>
                <div class="flex items-center justify-between mb-4">
                    <el-checkbox
                        id="rememberMe"
                        v-model="rememberMe"
                        label="Remember me"/>
                </div>
                <div class="text-center">
                    <el-button size="large" type="primary" @click="onLogin">
                        Login
                    </el-button>
                </div>
            </el-form>
        </div>
    </div>
</template>
<style scoped>

</style>
