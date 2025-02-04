import {RoleService} from '@/services/apiService';
import { handleError } from '@/utils';

export const useRoles = () => {

    const roles = ref([]);
    const role = ref(null);
    const permissions = ref([]);
    const unassignedPermissions = ref([]);

    async function fetchRoles(params = {}) {
        try {
            const { data } = await RoleService.getRoles(params);

            roles.value = data;

        } catch (error) {
            handleError(error);
        }
    }

    async function fetchRoleDetails(roleId) {
        try {
            const { data } = await RoleService.getRoleById(roleId);

            role.value = data;
        } catch (error) {
            handleError(error);
        }
    }

    async function fetchPermissions() {
        try {
            const { data } = await RoleService.getPermissions();

            permissions.value = data;

        } catch (error) {
            handleError(error);
        }
    }

    async function fetchUnassignedPermissions() {
        try {
            const { data } = await RoleService.getUnassignedPermissions();

            unassignedPermissions.value = data;

        } catch (error) {
            handleError(error);
        }
    }

    async function createRole(payload) {
        try {

            const res =  await RoleService.createRole(payload);

            return {res}
        } catch (error) {
            return { error: error };
        }
    }

    async function updateRole(roleId, payload) {
        try {

            const res =  await RoleService.updateRole(roleId, payload);

            role.value = res.data;

            return { res };

        } catch (error) {
            return { error: error };
        }
    }

    async function deleteRole(roleId, keepPermissions = false) {
        try {

            const res =  await RoleService.deleteRole(roleId, keepPermissions);

            roles.value = roles.value.filter(r => r.id !== roleId);

            return { res };

        } catch (error) {
            return { error: error };
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
