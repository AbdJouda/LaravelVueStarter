<script setup>
import {useCreateForm} from '@/hooks/useCreateForm';
import validationSchemas from '@/utils/validationSchemas';
import {useRoles} from './hooks/useRoles.js';
import { handleValidationError } from '@/utils';
import { RouteNames } from "@/constants/routeNames";

const route = useRoute();
const router = useRouter();

const roleId = route.params.id;
const selectedPermissions = ref([]);
const originalPermissions = ref([]);
const isDialogVisible = ref(false);

const {
    fetchRoleDetails,
    fetchPermissions,
    createRole,
    updateRole,
    role,
    permissions
} = useRoles();

const initialFormValues = {
    name: '',
    permissions: [],
};

const roleForm = useCreateForm({
    initialValues: initialFormValues,
    schema: validationSchemas.updateRoleSchema,
});

const {
    form: {errors, values, submitForm, resetForm, meta, setErrors, setFieldValue},
    fields,
    getErrorMessage,
} = roleForm;


onMounted(async () => {
    if (roleId) {
        await fetchRoleDetails(roleId);
        initializeFormData();
    }
    await fetchPermissions();
})

function initializeFormData() {
    if (role.value) {
        setFieldValue('name', role.value.display_name);
        selectedPermissions.value = role.value.permissions.map(permission => permission.attributes.id);
        setOriginalPermissions();
        resetForm({values: {...values}});
    }
}

const handleSubmit = async () => {
    if (!meta.value.valid) {
        submitForm();
        return;
    }

    roleId ? await handleRoleUpdate() : await handleRoleCreation();
};


const handleRoleUpdate = () => {
    const addedPermissions = selectedPermissions.value.filter(
        (permission) => !originalPermissions.value.includes(permission)
    );
    const removedPermissions = originalPermissions.value.filter(
        (permission) => !selectedPermissions.value.includes(permission)
    );

    if (addedPermissions.length || removedPermissions.length) {
        isDialogVisible.value = true;
    } else {
        updateExistingRole();
    }
};

const handleRoleCreation = async () => {
    const { payload, error } = await createRole({
        ...values,
        permissions: selectedPermissions.value,
    });

    if (error) return handleValidationError(error, setErrors);

    ElMessage.success({ message: payload?.message });

    router.push({name: RouteNames.LIST_ROLES});

};

const updateExistingRole = async (applyToUsers = false) => {
    const { payload, error } = await updateRole(role.value.id, {
        ...values,
        permissions: selectedPermissions.value,
        apply_to_users: applyToUsers,
    });

    if (error) return handleValidationError(error, setErrors);

    ElMessage.success({ message: payload?.message });
    isDialogVisible.value = false;
    setOriginalPermissions();
};


function setOriginalPermissions() {
    originalPermissions.value = [...selectedPermissions.value];
}
</script>
<template>
    <el-card class="mb-2 bg-white border-b dark:bg-gray-800 dark:border-gray-700 p-6" shadow="hover">
        <h2 class="text-lg font-semibold mb-4">Details</h2>
        <el-form label-position="top" label-width="100px">
            <el-form-item
                class="el-form-item--label-top"
                :error="getErrorMessage('name')"
                label="Role Name">
                <el-input
                    v-model="fields.name.value.value"
                    placeholder="Enter Role Name"
                    type="text"
                    @blur="fields.name.handleBlur"/>
            </el-form-item>
        </el-form>
        <div class="text-right" v-if="roleId">
            <el-button
                type="primary"
                @click="handleSubmit"
            >Update
            </el-button>
        </div>
    </el-card>
    <el-card class="mb-2 bg-white border-b dark:bg-gray-800 dark:border-gray-700 p-6" shadow="hover">
        <h2 class="text-lg font-semibold mb-4">Permissions</h2>
        <div class="grid grid-cols-3 gap-4 place-items-start">
            <div v-for="(permission, index) in permissions" :key="index">
                <el-checkbox-group v-model="selectedPermissions" border>
                    <el-checkbox
                        :label="permission.display_name"
                        :value="permission.id"
                        class="text-gray-700 focus:ring-2 focus:ring-indigo-500 rounded-md transition-all"
                    >
                    </el-checkbox>
                </el-checkbox-group>
            </div>
        </div>
        <div class="text-right">
            <el-button
                type="primary"
                @click="handleSubmit"
            >{{roleId ? 'Update' : 'Create'}}
            </el-button>
        </div>
    </el-card>
    <el-dialog
        v-if="roleId"
        v-model="isDialogVisible"
        title="Confirm Role Deletion"
        width="450px" >
        <div class="dialog-content">
            <p class="dialog-message">
                You are about to delete this role. Would you like to remove the permissions associated with this role
                from all existing users?
            </p>
        </div>
        <template #footer>
            <div class="dialog-footer">
                <el-button @click="isDialogVisible = false">Cancel</el-button>
                <el-button type="warning"  @click="updateExistingRole(true)">Apply to Users</el-button>
                <el-button type="primary" @click="updateExistingRole()">Do Not Apply</el-button>
            </div>
        </template>
    </el-dialog>
</template>

<style scoped>

</style>
