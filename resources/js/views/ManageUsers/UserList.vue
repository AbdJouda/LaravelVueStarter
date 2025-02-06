<script setup>
import { useUsers } from "./hooks/useUsers";
import { useTable } from "@/hooks/useTable";
import { RouteNames } from "@/constants/routeNames";
import { handleError } from '@/utils';

const {
    fetchUsers,
    toggleUserStatus,
    resetUserPassword,
    users,
    pagination
} = useUsers();

const router = useRouter();

const {
    sortBy,
    searchText,
    currentPage,
    pageSize,
    total,
    handleSortChange,
    handlePageChange,
    handlePageSizeChange,
} = useTable(fetchUsers, pagination);

const filterTag = (value, row) => row.is_active.row === value;

const changeStatus = async (user) =>{

    const {res, error} = await toggleUserStatus(user.id);

    if (error) {
        handleError(error);
        return;
    }

    ElMessage.success({
        message: res.message,
    });


}
const resetPassword = async (user) =>{

    const {res, error} = await resetUserPassword(user.id);

    if (error) {
        handleError(error);
        return;
    }

    ElMessage.success({
        message: res?.message,
    });

}

const confirmResetPassword = (user) => {
    ElMessageBox.confirm(
        `<strong>Are you sure you want to reset the user's password?</strong>
         <br>The user will receive an email with their new password.`,
        {
            dangerouslyUseHTMLString: true,
            confirmButtonText: "Reset",
            cancelButtonText: "Cancel",
            type: "warning",
        }
    )
        .then(() => resetPassword(user))
        .catch(() => {
        });
};


</script>
<template>
    <div class="mb-2 text-right">
        <el-button
            v-hasPermission="`create_users`"
            type="primary"
            @click="() => router.push({ name: RouteNames.ADD_USER})"
        >Add new user
        </el-button>
    </div>
    <el-table :data="users" stripe @sort-change="handleSortChange">
        <el-table-column label="Name" prop="name" sortable="custom" width="220" >
            <template #default="scope">
                <div class="flex items-center gap-3">
                    <el-avatar :size="40" :src="scope.row.profile_photo_url" style="margin-right: 10px;"/>
                    {{ scope.row.name }}
                </div>
            </template>
        </el-table-column>
        <el-table-column label="Email" prop="email" show-overflow-tooltip/>
        <el-table-column label="Phone" prop="phone"/>
        <el-table-column label="Roles" prop="roles"  width="150" show-overflow-tooltip>
            <template #default="scope">
                <el-tag
                    v-for="(role,index) in scope.row.roles"
                    :key="index"
                    class="capitalize mr-1"
                    disable-transitions
                    effect="dark"
                    round
                    size="small"
                    type="primary">
                    {{ role.display_name }}
                </el-tag>
            </template>
        </el-table-column>
        <el-table-column label="Permissions" prop="permissions_count"/>
        <el-table-column
            :filter-method="filterTag"
            :filters="[
                    { text: 'Active', value: true },
                    { text: 'Inactive', value: false }]"
            filter-placement="bottom-end"
            label="Status"
            prop="is_active">
            <template #default="scope">
                <el-tag
                    :type="scope.row.is_active.row ? 'primary' : 'danger'"
                    disable-transitions
                >{{ scope.row.is_active.text }}
                </el-tag
                >
            </template>
        </el-table-column>

        <el-table-column fixed="right" width="200">
            <template #header>
                <el-input v-model="searchText" clearable placeholder="Search" size="small"/>
            </template>
            <template #default="scope">
                <div  class="flex space-x-2" v-hasPermission="`edit_users`">
                <el-tooltip content="Edit"
                            placement="bottom"
                            effect="light">
                    <font-awesome-icon :icon="['fas', 'pen-to-square']"
                                       class="fa-lg text-blue-500 cursor-pointer"
                                       @click.stop="
                () => router.push({ name: RouteNames.EDIT_USER, params: { id: scope.row.id } })"/>
                </el-tooltip>
                <el-tooltip :content="scope.row.is_active.row ? 'Deactivate User' : 'Activate User'"
                            placement="bottom"
                            effect="light">
                    <font-awesome-icon
                        :icon="['fas', scope.row.is_active.row ? 'ban' : 'circle-check']"
                        class="cursor-pointer fa-lg"
                        :class="{ 'text-green-500': !scope.row.is_active.row, 'text-red-500': scope.row.is_active.row }"
                        @click.stop="changeStatus(scope.row)"
                    />
                </el-tooltip>
                    <el-tooltip content="Reset User Password"
                                placement="bottom"
                                effect="light">
                        <font-awesome-icon
                            :icon="['fas', 'key']"
                            class="cursor-pointer text-yellow-500 fa-lg"
                            @click.stop="confirmResetPassword(scope.row)"
                        />
                    </el-tooltip>
                </div>
            </template>
        </el-table-column>
    </el-table>
    <el-pagination
        :current-page="currentPage"
        :hide-on-single-page="total <= pageSize"
        :page-size="pageSize"
        :page-sizes="[10, 20, 30, 40, 50, 100]"
        :total="total"
        background
        class="mt-4"
        layout="prev, pager, next, sizes"
        @update:current-page="handlePageChange"
        @update:page-size="handlePageSizeChange"
    />

</template>


<style scoped>

</style>
