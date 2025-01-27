import {RoleService} from '@/services/apiService';

export const useRoles = () => {

    const roles = ref([]);
    const role = ref(null);
    const permissions = ref([]);
    const unassignedPermissions = ref([]);

    const handleError = (error) => {
        if (error.payload) {
            ElMessage.error({
                message: error.payload.message,
            });
        } else {
            console.error('Error:', error.message);
        }
        return { error: error };
    };

    async function fetchRoles(params = {}) {
        try {
            const { payload } = await RoleService.getRoles(params);

            const resData = payload.data;

            roles.value = resData.map((el) => ({
                ...el.attributes,
                ...el.relations,
            }));

        } catch (error) {
            handleError(error);
        }
    }

    async function fetchRoleDetails(roleId) {
        try {
            const { payload, error } = await RoleService.getRoleById(roleId);

            const { attributes, relations } = payload.data;

            role.value = { ...attributes, ...relations };
        } catch (error) {
            handleError(error);
        }
    }

    async function fetchPermissions() {
        try {
            const { payload } = await RoleService.getPermissions();

            const resData = payload.data;

            permissions.value = resData.map((el) => ({
                ...el.attributes,
            }));

        } catch (error) {
            handleError(error);
        }
    }

    async function fetchUnassignedPermissions() {
        try {
            const { payload } = await RoleService.getUnassignedPermissions();

            const resData = payload.data;

            unassignedPermissions.value = resData.map((el) => ({
                ...el.attributes,
            }));

        } catch (error) {
            handleError(error);
        }
    }

    async function createRole(data) {
        try {

            const { payload, error } =  await RoleService.createRole(data);

            return { payload };

        } catch (_error) {
            return { error: _error };
        }
    }

    async function updateRole(roleId, data) {
        try {

            const { payload, error } =  await RoleService.updateRole(roleId, data);

            const { attributes, relations } = payload.data;

            role.value = { ...attributes, ...relations };

            return { payload };

        } catch (_error) {
            return { error: _error };
        }
    }

    async function deleteRole(roleId, keepPermissions = false) {
        try {

            const { payload, error } =  await RoleService.deleteRole(roleId, keepPermissions);

            roles.value = roles.value.filter(r => r.id !== roleId);

            return { payload };

        } catch (_error) {
            return { error: _error };
        }
    }


    return {
        fetchRoles,
        fetchRoleDetails,
        fetchPermissions,
        fetchUnassignedPermissions,
        createRole,
        updateRole,
        deleteRole,
        roles,
        role,
        permissions,
        unassignedPermissions,
    };
};
