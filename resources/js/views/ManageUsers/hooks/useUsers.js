import {UserService} from '@/services/apiService';
import {handleError} from '@/utils';

export const useUsers = () => {

    const users = ref([]);
    const user = ref(null);
    const pagination = ref(null);

    async function fetchUsers(params = {}) {
        try {
            const {data, meta} = await UserService.getUsers(params);

            users.value = data;
            pagination.value = meta.pagination;
        } catch (error) {
            handleError(error);
        }
    }

    async function fetchUserDetails(userId) {
        try {
            const {data} = await UserService.getUserById(userId);

            user.value = data;

        } catch (error) {
            handleError(error);
        }
    }

    async function createUser(payload) {
        try {

            const res = await UserService.createUser(payload);

            user.value = res.data;

            return {res};

        } catch (error) {
            return {error: error};
        }
    }

    async function updateUser(userId, payload) {
        try {

            const res = await UserService.updateUser(userId, payload);

            user.value = res.data;

            return {res};

        } catch (error) {
            return {error: error};
        }
    }


    async function toggleUserStatus(userId) {
        try {

            const res = await UserService.toggleUserStatus(userId);

            users.value = users.value.map((el) =>
                el.id === userId
                    ? {
                        ...el,
                        is_active: {
                            row: !el.is_active.row,
                            text: !el.is_active.row ? 'Active' : 'Inactive',
                        },
                    }
                    : el
            );

            return {res};

        } catch (error) {
            return {error: error};
        }
    }


    async function resetUserPassword(userId) {
        try {

            const res =  await UserService.resetUserPassword(userId);

            return {res};

        } catch (error) {
            return {error: error};
        }
    }

    return {
        fetchUsers,
        fetchUserDetails,
        toggleUserStatus,
        createUser,
        updateUser,
        resetUserPassword,
        users,
        user,
        pagination,
    };
};
