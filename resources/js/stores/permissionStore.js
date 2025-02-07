import {defineStore} from 'pinia';
import {ProfileService} from '@/services/apiService';

const usePermissionStore = defineStore('permissions', () => {
    const roles = ref([]);
    const permissions = ref([]);
    const hasLoadedPermissions = ref(false);

    const initializeRoles = async () => {
        if (roles.value.length === 0) {
            await fetchRoles();
        }

    };

    async function fetchRoles(noGlobalLoading = false) {

        try {
            const {data} = await ProfileService.getRoles(noGlobalLoading);

            roles.value = data.roles;
            permissions.value = data.permissions;
            hasLoadedPermissions.value = true;
        } catch (error) {
            console.error("Error fetching roles:", error);
        }
    }

    const checkPermission = (permission) => {

        if (roles.value.find(role => role.name === 'admin')) {
            return true;
        }
        return Array.isArray(permission)
            ? hasAnyPermission(permission)
            : hasPermission(permission);
    };

    const hasPermission = (permission) => {
        return permissions.value.some(p => p.name === permission);
    };

    const hasAnyPermission = (permissionsArray = []) => {
        return permissionsArray.some(permission => hasPermission(permission));
    };


    return {
        roles,
        permissions,
        hasLoadedPermissions,
        fetchRoles,
        initializeRoles,
        checkPermission,
    };
});

export {usePermissionStore};
