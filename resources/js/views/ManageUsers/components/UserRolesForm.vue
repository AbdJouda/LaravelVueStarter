<script setup>
import {useRoles} from "../hooks/useRoles";
import _ from "lodash";
import { RouteNames } from "@/constants/routeNames";

const props = defineProps({
    selectedUser: {
        type: Object,
        required: false
    },
});

const emit = defineEmits(['submit-form', 'update-permissions']);

const {
    fetchRoles,
    fetchUnassignedPermissions,
    roles,
    unassignedPermissions,
} = useRoles();

const checkedRoles = ref([]);
const checkedPermissions = ref([]);
const searchText = ref(null);
const router = useRouter();

onMounted(() => {
    initializeFormData()
})

watch(
    () => props.selectedUser,
    (newUser) => {
        if (newUser) {

            checkedRoles.value = newUser.roles?.map((role) => role.id);
            checkedPermissions.value = newUser.permissions?.map(
                (permission) => permission.id
            );
        }
    },
    {immediate: true}
);

async function initializeFormData() {
    try {
        await Promise.all([fetchRoles(), fetchUnassignedPermissions()]);

    } catch (error) {
        console.error("Error initializing form data:", error);
    }
}

const handleSearch = _.debounce(async () => {
    await fetchRoles({search: searchText.value});
}, 300);

watch(searchText, handleSearch);

function toggleRole(role) {
    if (isRoleChecked(role.id)) {
        checkedRoles.value = checkedRoles.value.filter((id) => id !== role.id);
        checkedPermissions.value = checkedPermissions.value.filter(
            (permId) =>
                !role.permissions.some(
                    (permission) => permission.id === permId
                )
        );
    } else {
        checkedRoles.value?.push(role.id);
        role.permissions.forEach((permission) => {
            if (!checkedPermissions.value?.includes(permission.id)) {
                checkedPermissions.value?.push(permission.id);
            }
        });
    }

    emitChanges();

}

function isRoleChecked(roleId) {
    return checkedRoles.value?.includes(roleId);
}


function handlePermissionChange(role) {
    const allPermissionsSelected = role.permissions.every((permission) =>
        checkedPermissions.value.includes(permission.id)
    );

    if (allPermissionsSelected) {
        if (!isRoleChecked(role.id)) {
            checkedRoles.value?.push(role.id);
        }
    } else {
        checkedRoles.value = checkedRoles.value?.filter((id) => id !== role.id);
    }
    emitChanges();
}


async function handleSubmit() {
    emit('submit-form');
}

async function emitChanges() {
    emit('update-permissions', {roles: checkedRoles.value, permissions: checkedPermissions.value});
}
</script>
<template>
    <div class="mb-6">
        <div class="flex justify-between mb-4">
            <h3 class="text-lg my-2">Roles & Permissions</h3>
            <div class="pr-5">
                <el-input v-model="searchText" clearable placeholder="Search Permissions"/>
            </div>
        </div>
        <div>
            <el-card v-if="unassignedPermissions.length"
                     class="mb-2 bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                     shadow="hover">
                <h4 class="font-semibold mb-4">
                    Permissions Without Roles
                    <el-popover
                        :width="200"
                        placement="top"
                        trigger="hover">
                        <template #reference>
                            <font-awesome-icon :icon="['fas', 'circle-question']"/>
                        </template>
                        These permissions are either unassigned to any roles or were previously associated with a role
                        that has since been deleted.
                        You can organize them by assigning them to a role on the
                        <el-link type="primary" @click="() => router.push({ name: RouteNames.ADD_ROLE})">Add Role
                        </el-link>
                        page.
                    </el-popover>
                </h4>
                <el-checkbox-group v-model="checkedPermissions" border>
                    <div class="grid grid-cols-5 gap-2 ml-2">
                        <el-checkbox
                            v-for="unassignedPermission in unassignedPermissions" :key="unassignedPermission.id"
                            :label="unassignedPermission.display_name"
                            :value="unassignedPermission.id"
                            @change="emitChanges"
                            class="text-gray-700 focus:ring-2 focus:ring-indigo-500 rounded-md transition-all"
                        >
                        </el-checkbox>
                    </div>

                </el-checkbox-group>
            </el-card>
            <el-card v-for="role in roles"
                     :key="role.id"
                     class="mb-2 bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                     shadow="hover">
                <div class="mb-2">
                    <el-checkbox
                        :model-value="isRoleChecked(role.id)"
                        :value="role.id"
                        border
                        @update:model-value="toggleRole(role)"
                    >
                        <p>{{ role.display_name }}</p>
                    </el-checkbox>

                </div>
                <div class="grid grid-cols-5 gap-2 ml-2">
                    <div v-for="permission in role.permissions" :key="permission.id">
                        <el-checkbox-group v-model="checkedPermissions" border>
                            <el-checkbox
                                :label="permission.display_name"
                                :value="permission.id"
                                class="capitalize text-gray-700 focus:ring-2 focus:ring-indigo-500 rounded-md transition-all"
                                @change="handlePermissionChange(role)"
                            >
                            </el-checkbox>
                        </el-checkbox-group>
                    </div>
                </div>
            </el-card>

        </div>
        <div class="mt-6 text-right">
            <el-button
                type="primary"
                @click="handleSubmit"
            >{{selectedUser ? 'Update' : 'Create'}}
            </el-button>
        </div>
    </div>
</template>

<style scoped>

</style>
