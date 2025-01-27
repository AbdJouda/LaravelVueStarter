<script setup>
import {useCreateForm} from '@/hooks/useCreateForm';
import validationSchemas from '@/utils/validationSchemas';
import {handleValidationError} from '@/utils';
import {AuthService} from '@/services/apiService';
import {useAuthStore} from '@/stores/authStore';
import {RouteNames} from '@/constants/routeNames'

const {username, code} = defineProps({
    username: {
        type: String,
        required: true,
    },
    code: {
        type: String,
        default: '',
    },
});
const authStore = useAuthStore()
const router = useRouter();

const initialFormValues = {
    password : '',
    password_confirmation : '',
};

const resetPassForm = useCreateForm({
    initialValues: initialFormValues,
    schema: validationSchemas.resetPassSchema,
});

const {
    form: {values, submitForm, resetForm, meta, setErrors},
    fields,
    getErrorMessage,
} = resetPassForm;

const onReset = async () => {
    if (!meta.value.valid) {
        submitForm();
        return;
    }

    if (!code) return;


    const data = {
        username,
        code,
        ...values,
    };

    try {

        const {payload} = await AuthService.resetPassword(data);

        await authStore.updateAuthState(payload);

        ElMessage.success({
            message: payload.message,
        });

        await router.push({name: RouteNames.DASHBOARD});

    } catch (error) {
        handleValidationError(error, setErrors);
    }

}


</script>
<template>
    <div class="divide-y divide-gray-200">
        <div class="py-4 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
            <el-form class="space-y-6">
                <el-form-item
                    :error="getErrorMessage('password')"
                    class="el-form-item--label-top"
                    label="Password">
                    <el-input
                        v-model="fields.password.value.value"
                        class="h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:borer-rose-600"
                        placeholder="New Password"
                        type="password"
                        @blur="fields.password.handleBlur">
                        <template #prefix>
                            <font-awesome-icon :icon="['fas', 'lock']"/>
                        </template>
                    </el-input>
                </el-form-item>
                <el-form-item
                    :error="getErrorMessage('password_confirmation')"
                    class="el-form-item--label-top mt-2"
                    label="Confirm your password">
                    <el-input
                        v-model="fields.password_confirmation.value.value"
                        class="h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:borer-rose-600"
                        placeholder="Password Confirmation"
                        type="password"
                        @blur="fields.password_confirmation.handleBlur">
                        <template #prefix>
                            <font-awesome-icon :icon="['fas', 'lock']"/>
                        </template>
                    </el-input>
                </el-form-item>
                <div class="text-center">
                    <el-button size="large" type="primary" @click.prevent="onReset">
                        Reset
                    </el-button>
                </div>
            </el-form>

        </div>
    </div>
</template>
<style scoped>

</style>
