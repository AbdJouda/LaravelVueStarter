import {UserService} from '@/services/apiService';

export const useUsers = () => {

    const users = ref([]);
    const user = ref(null);
    const pagination = ref(null);

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

    async function fetchUsers(params = {}) {
        try {
            const { payload } = await UserService.getUsers(params);

            const resData = payload.data;

            users.value = resData.map((el) => ({
                ...el.attributes,
                ...el.relations,
            }));
            pagination.value = payload.meta;
        } catch (error) {
            handleError(error);
        }
    }

    async function fetchUserDetails(userId) {
        try {
            const response = await UserService.getUserById(userId);

            const { attributes, relations } = response.payload.data;

            user.value = { ...attributes, ...relations };

        } catch (error) {
            handleError(error);
        }
    }

    async function createUser( data) {
        try {

            const { payload, error } = await UserService.createUser(data);

            const { attributes, relations } = payload.data;

            user.value = { ...attributes, ...relations };

            return { payload };

        } catch (_error) {
            return { error: _error };
        }
    }

    async function updateUser(userId, data) {
        try {

            const { payload, error } = await UserService.updateUser(userId, data);

            const { attributes, relations } = payload.data;

            user.value = { ...attributes, ...relations };

            return { payload };

        } catch (_error) {
            return { error: _error };
        }
    }



    async function toggleUserStatus(userId) {
        try {

            const { payload } = await UserService.toggleUserStatus(userId);

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

            return { payload };

        } catch (error) {
            handleError(error);
        }
    }


    async function resetUserPassword(userId) {
        try {

            const { payload, error } = await UserService.resetUserPassword(userId);

            return { payload };

        } catch (_error) {
            return { error: _error };
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
