<script setup>
import { useRoles } from "./hooks/useRoles";
import { RouteNames } from "@/constants/routeNames";
import { useTable } from "@/hooks/useTable";
import { handleError } from "@/utils";


const { fetchRoles, deleteRole: deleteRoleApi, roles } = useRoles();
const router = useRouter();

const isDialogVisible = ref(false);
const selectedRole = ref(null);

const {
    sortBy,
    searchText,
    currentPage,
    total,
    handleSortChange,
} = useTable(fetchRoles);

const deleteRole = async (keepPermissions = false) => {

    const { res, error } = await deleteRoleApi(selectedRole.value.id, keepPermissions);

    resetDialog();

    if (error) {
        handleError(error);
        return;
    }

    ElMessage.success({
        message: res?.message,
    });

};
const showConfirmDialog = (role, hasAssociatedUser = false) => {
    selectedRole.value = role;
    isDialogVisible.value = hasAssociatedUser;
    if(!hasAssociatedUser)
        deleteRole(true)
};

const resetDialog = () => {
    selectedRole.value = null;
    isDialogVisible.value = false;
};

</script>
<template>
    <div class="mb-2 text-right">
        <el-button
            v-hasPermission="`add_roles`"
            type="primary"
            @click="() => router.push({ name: RouteNames.ADD_ROLE})"
        >Add new role
        </el-button>
    </div>
    <el-table :data="roles" stripe @sort-change="handleSortChange">
        <el-table-column label="Name" prop="display_name"/>
        <el-table-column label="Associated Users" prop="users_count"/>
        <el-table-column label="Associated Permissions" prop="permissions_count"/>
        <el-table-column fixed="right" width="200">
            <template #header>
                <el-input v-model="searchText" clearable placeholder="Search" size="small"/>
            </template>
            <template #default="scope">
                <div class="flex space-x-2">
                    <el-tooltip content="Edit" effect="light" placement="bottom" >
                        <font-awesome-icon :icon="['fas', 'pen-to-square']"
                                           class="fa-lg text-blue-500 cursor-pointer"
                                           v-hasPermission="`edit_roles`"
                                           @click.stop="
                () => router.push({ name: RouteNames.EDIT_ROLE, params: { id: scope.row.id } })"/>
                    </el-tooltip>
                    <el-tooltip content="Delete"
                                effect="light"
                                placement="bottom">
                        <font-awesome-icon
                            :icon="['fas', 'trash']"
                            v-hasPermission="`delete_roles`"
                            class="cursor-pointer text-red-500 fa-lg"
                            @click="showConfirmDialog(scope.row, scope.row.users_count !== 0)"
                        />
                    </el-tooltip>
                </div>
            </template>
        </el-table-column>
    </el-table>
    <el-dialog
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
                <el-button type="warning"  @click="deleteRole(true)">Keep Permissions</el-button>
                <el-button type="primary" @click="deleteRole()">Remove Permissions</el-button>
            </div>
        </template>
    </el-dialog>


</template>


<style scoped>

</style>
