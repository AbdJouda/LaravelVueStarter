<script setup>
import {useProfile} from '../hooks/useProfile';
import {handleError} from '@/utils';
import {useCreateForm} from '@/hooks/useCreateForm';
import validationSchemas from '@/utils/validationSchemas';

const {updatePassword} = useProfile();

const initialValues = {
    password: '',
    new_password: '',
    new_password_confirmation: '',
};

const passwordForm = useCreateForm({
    initialValues,
    schema: validationSchemas.updatePasswordSchema,
});

const {
    form: {errors, values, submitForm, meta, resetForm, setErrors},
    fields,
    getErrorMessage
} = passwordForm;

const updateUserPassword = async () => {

    if (!meta.value.valid) {
        submitForm();
        return;
    }

    const {res, error} = await updatePassword(values);


    if (error) {
        handleError(error, setErrors);
        return;
    }

    ElMessage.success({
        message: res?.message,
    });

    resetForm();

};
</script>
<template>
    <div id="change-password">
        <div class="flex-1">
            <!-- Basic Info Form -->
            <el-card class="rounded-lg mb-3 dark:bg-gray-800 dark:border-gray-700" shadow="hover">
                <h2 class="text-lg font-medium mb-4">Change Password</h2>
                <el-form label-position="top">
                    <div
                        class="flex flex-col items-center w-full space-x-0 space-y-2 sm:flex-row sm:space-x-4 sm:space-y-0 ">
                        <div class="w-full">
                            <el-form-item
                                class="el-form-item--label-top"
                                :error="getErrorMessage('password')"
                                label="Password">
                                <el-input
                                    v-model="fields.password.value.value"
                                    placeholder="Enter Current Password"
                                    type="password"
                                    @blur="fields.password.handleBlur"/>
                            </el-form-item>
                            <el-form-item
                                class="el-form-item--label-top"
                                :error="getErrorMessage('new_password')"
                                label="New password">
                                <el-input
                                    v-model="fields.new_password.value.value"
                                    placeholder="Enter New Password"
                                    type="password"
                                    show-password
                                    @blur="fields.new_password.handleBlur"/>
                            </el-form-item>
                            <el-form-item
                                class="el-form-item--label-top"
                                :error="getErrorMessage('new_password_confirmation')"
                                label="password">
                                <el-input
                                    v-model="fields.new_password_confirmation.value.value"
                                    placeholder="Confirm New password"
                                    type="password"
                                    show-password
                                    @blur="fields.new_password_confirmation.handleBlur"/>
                            </el-form-item>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <el-button
                            type="primary"
                            @click="updateUserPassword"
                        >Update
                        </el-button>
                    </div>
                </el-form>
            </el-card>
        </div>
    </div>
</template>

<style scoped>

</style>
