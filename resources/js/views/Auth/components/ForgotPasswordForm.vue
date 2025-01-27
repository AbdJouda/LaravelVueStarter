<script setup>
import {useCreateForm} from '@/hooks/useCreateForm';
import validationSchemas from '@/utils/validationSchemas';
import {handleValidationError} from '@/utils';
import {AuthService} from '@/services/apiService';

const router = useRouter();
const route = useRoute();

const initialFormValues = {
    username: '',
};

const forgotForm = useCreateForm({
    initialValues: initialFormValues,
    schema: validationSchemas.forgotPasswordSchema,
});

const {
    form: {values, submitForm, resetForm, meta, setErrors},
    fields,
    getErrorMessage,
} = forgotForm;

const onSendForgotPasswordRequest = async () => {

    if (!meta.value.valid) {
        submitForm();
        return;
    }

    try {

        const {payload} = await AuthService.forgetPassword({username: values.username});

        ElMessage.success({
            message: payload.message,
        });

        fields.username.resetField();
    } catch (error) {
        handleValidationError(error, setErrors);
    }
}

</script>
<template>
    <div class="divide-y divide-gray-200">
        <div class="py-4 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
            <el-form>
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
                <div class="text-center">
                    <el-button size="large"
                               type="primary"
                               @click.prevent="onSendForgotPasswordRequest">Submit
                    </el-button>
                </div>
            </el-form>
        </div>
    </div>
</template>

