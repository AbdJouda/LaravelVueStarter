<script setup>
import {useCreateForm} from '@/hooks/useCreateForm';
import validationSchemas from '@/utils/validationSchemas';
import UserRolesForm from "./components/UserRolesForm.vue";
import {useUsers} from "./hooks/useUsers.js";
import {handleError} from '@/utils';
import {RouteNames} from "@/constants/routeNames";

const route = useRoute();
const router = useRouter();

const userId = route.params.id;

const {fetchUserDetails, createUser, updateUser, user} = useUsers();

const initialFormValues = {
    name: '',
    email: '',
    phone: '',
    is_active: true,
    roles: [],
    permissions: [],
};

const userForm = useCreateForm({
    initialValues: initialFormValues,
    schema: validationSchemas.userSchema,
});

const {
    form: {errors, values, submitForm, resetForm, meta, setErrors, setFieldValue},
    fields,
    getErrorMessage,
} = userForm;

onMounted(async () => {
    if (userId) {
        await fetchUserDetails(userId);
        initializeFormData();
    }
})

function initializeFormData() {
    const formattedUser = {
        ...user.value,
        is_active: user.value.is_active?.row,
        roles: user.value.roles.map(el => el.id),
        permissions: user.value.permissions.map(el => el.id)

    };

    resetForm({values: {...formattedUser}});
}

async function handleSubmit() {

    if (!meta.value.valid) {
        submitForm();
        return;
    }

    const method = userId
        ? updateUser(user.value.id, values)
        : createUser(values)

    const {res, error} = await method;

    if (error) {
        handleError(error, setErrors);
        return;
    }

    ElMessage.success({
        message: res?.message,
    });

    if (!userId)
        router.push({name: RouteNames.LIST_USERS});
}

const handleRoleUpdateEvent = (value) => {
    setFieldValue('roles', value.roles);
    setFieldValue('permissions', value.permissions);

};
</script>
<template>
    <el-card class="mb-2 bg-white border-b dark:bg-gray-800 dark:border-gray-700 p-6" shadow="hover">
        <h2 class="text-lg font-semibold mb-4">Details</h2>
        <el-form label-position="top" label-width="100px">
            <el-form-item
                :error="getErrorMessage('name')"
                class="el-form-item--label-top"
                label="Name">
                <el-input
                    v-model="fields.name.value.value"
                    type="text"
                    @blur="fields.name.handleBlur"/>
            </el-form-item>
            <el-form-item
                :error="getErrorMessage('email')"
                class="el-form-item--label-top"
                label="Email">
                <el-input
                    v-model="fields.email.value.value"
                    type="text"
                    @blur="fields.email.handleBlur"/>
            </el-form-item>
            <el-form-item
                :error="getErrorMessage('phone')"
                class="el-form-item--label-top"
                label="Phone">
                <el-input
                    v-model="fields.phone.value.value"
                    type="text"
                    @blur="fields.phone.handleBlur"/>
            </el-form-item>
            <el-form-item
                class="el-form-item--label-top"
                label="Status">
                <el-switch
                    v-model="fields.is_active.value.value"
                    active-text="Active"
                    inactive-text="Inactive"
                    size="large"
                />
            </el-form-item>
        </el-form>
        <div v-if="userId" class="mt-6 text-right">
            <el-button
                type="primary"
                @click="handleSubmit"
            >Update
            </el-button>
        </div>
    </el-card>
    <UserRolesForm v-has-permission="`assign_roles`"
                   :selected-user="user"
                   @submit-form="handleSubmit"
                   @update-permissions="handleRoleUpdateEvent"/>
</template>


<style scoped>

</style>
