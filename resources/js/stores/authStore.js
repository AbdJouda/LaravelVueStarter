import {defineStore} from 'pinia';
import {AuthService} from '@/services/apiService';
import {getStorage, removeStorage, saveStorage} from '@/services/storageService';
import {usePermissionStore} from '@/stores/permissionStore';

const useAuthStore = defineStore('auth', () => {
    const user = ref(null);
    const token = ref(null);
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
            const res = await AuthService.login({username, password});

            await updateAuthState(res);

            return {res};
        } catch (error) {
            return {error: error};
        }
    }

    async function socialLogin(provider, code) {
        try {
            const res = await AuthService.login({provider, code});
            await updateAuthState(res);
            return { res };
        } catch (error) {
            return { error: error };

        }
    }

    async function logout() {
        try {
            const {data} = await AuthService.logout();
            clearAuthData();
            return {data: data};
        } catch (error) {
            return {error: error};
        }
    }

    async function updateAuthState(payload) {
        const {data, meta} = payload
        setUser(data);
        token.value = `${meta.access_type} ${meta.token}`;
        saveStorage('authToken', token.value);
        await permissionStore.initializeRoles();
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


    return {
        user,
        token,
        initializeAuthentication,
        updateAuthState,
        login,
        socialLogin,
        logout,
        setUser,
        clearAuthData,
    };
});

export {useAuthStore};
