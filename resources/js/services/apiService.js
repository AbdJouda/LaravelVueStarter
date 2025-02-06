import {httpService} from './httpService';

export const SettingsService = {
    getSettings() {
        return httpService.get(`/shared/settings`);
    },
    updateSettings(data) {
        return httpService.post(`/admin/settings`, data,
            {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
    },
};

export const AuthService = {
    login(data) {
        return httpService.post(`/shared/auth/login`, data);
    },
    forgetPassword(data) {
        return httpService.post(`/shared/auth/forgot-password`, data);
    },
    verifyResetCode(data) {
        return httpService.post(`/shared/auth/verify-reset-code`, data);
    },
    resetPassword(data) {
        return httpService.post(`/shared/auth/reset-password`, data);
    },
    logout() {
        return httpService.get(`/shared/auth/logout`);
    },
};

export const ProfileService = {
    getProfile() {
        return httpService.get(`/shared/profile`);
    },
    getRoles(noGlobalLoading = false) {
        return httpService.get(`/shared/profile/roles`, {noGlobalLoading});
    },
    updateProfileInfo(data) {
        return httpService.post(`/shared/profile/update`, data,
            {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
    },
    updatePassword(data) {
        return httpService.post(`/shared/profile/update-password`, data);
    },
    deleteAccount(data) {
        return httpService.post(`/shared/profile/request-account-delete`, data);
    },
    getNotifications(params, noGlobalLoading = true) {
        return httpService.get(`/shared/profile/notifications`, {params, noGlobalLoading});
    },
};

export const UserService = {
    getUsers(params) {
        return httpService.get(`/admin/users`, {params});
    },
    getUserById(userId) {
        return httpService.get(`/admin/users/${userId}/details`);
    },
    createUser(data) {
        return httpService.post(`/admin/users/create`, data);
    },
    updateUser(userId, data) {
        return httpService.patch(`/admin/users/${userId}/update`, data);
    },
    resetUserPassword(userId) {
        return httpService.get(`/admin/users/${userId}/reset-password`);
    },
    toggleUserStatus(userId) {
        return httpService.patch(`/admin/users/${userId}/toggle-status`);
    },
};
export const RoleService = {
    getRoles(params) {
        return httpService.get(`/admin/roles`, {params});
    },
    getRoleById(roleId) {
        return httpService.get(`/admin/roles/${roleId}/details`);
    },
    getPermissions() {
        return httpService.get(`/admin/roles/permissions`);
    },
    getUnassignedPermissions() {
        return httpService.get(`/admin/roles/unassigned-permissions`);
    },
    createRole(data) {
        return httpService.post(`/admin/roles/create`, data);
    },
    updateRole(roleId, data) {
        return httpService.patch(`/admin/roles/${roleId}/update`, data);
    },
    deleteRole(roleId, keepPermissions = false) {
        return httpService.delete(`/admin/roles/${roleId}/delete?keep_permissions=${keepPermissions}`);
    },
};
export const TodoService = {
    getTodos(params) {
        return httpService.get(`/shared/todos`, {params});
    },
    createTodo(data) {
        return httpService.post(`/shared/todos/create`, data);
    },
    updateTodo(todoId, data) {
        return httpService.patch(`/shared/todos/${todoId}/update`, data);
    },
    deleteTodo(todoId) {
        return httpService.delete(`/shared/todos/${todoId}/delete`);
    },
    toggleCompleteStatus(todoId) {
        return httpService.patch(`/shared/todos/${todoId}/toggle-complete-status`);
    },
};
