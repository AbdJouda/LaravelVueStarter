import { defineStore } from 'pinia';
import { AuthService } from '@/services/apiService';
import { getStorage, removeStorage, saveStorage } from '@/services/storageService';
import { usePermissionStore } from '@/stores/permissionStore';

const useAuthStore = defineStore('auth', () => {
    const user = ref(null);
    const token = ref(null);
    const error = ref(null);
    const permissionStore = usePermissionStore();

    async function initializeAuthentication() {
        try {
            const savedUser = getStorage('currentUser');
            const savedToken = getStorage('authToken');

            if (savedUser && savedToken) {
                user.value = savedUser;
                token.value = savedToken;
            }
        } catch (error) {
            console.error('Authentication initialization failed', error);
        }
    }

    async function login(username, password) {
        try {
            const { payload, error } = await AuthService.login({ username, password });

            updateAuthState(payload);

            return { payload };
        } catch (error) {
            handleError(error);
            return { error };
        }
    }

    async function logout() {
        try {
            const { payload } = await AuthService.logout();
            clearAuthData();
            return { data: payload };
        } catch (error) {
            return { error };
        }
    }

    async function updateAuthState(payload) {
        const { attributes, relations } = payload.data || {};
        if (attributes) {
            setUser(attributes);
            token.value = `${payload.meta.access_type} ${payload.meta.token}`;
            saveStorage('authToken', token.value);
            await permissionStore.initializeRoles();
        } else {
            throw new Error('Invalid payload structure');
        }
    }


    function setUser(userData) {
        user.value = userData;
        saveStorage('currentUser', userData);
    }

    function clearAuthData() {
        ['currentUser', 'authToken'].forEach(removeStorage);
        user.value = null;
        token.value = null;
    }

    function handleError(err) {
        error.value = err?.response?.data?.message || err.message || 'An error occurred';
    }

    return {
        user,
        token,
        error,
        initializeAuthentication,
        updateAuthState,
        login,
        logout,
        setUser,
        clearAuthData,
    };
});

export { useAuthStore };
