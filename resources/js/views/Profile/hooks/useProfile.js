import {ProfileService} from '@/services/apiService';

export const useProfile = () => {
    const profile = ref(null);

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

    async function fetchProfile() {
        try {
            const { payload, error } = await ProfileService.getProfile();

            const { attributes, relations } = payload.data;

            profile.value = { ...attributes, ...relations };

        } catch (error) {
            handleError(error);
        }
    }


    async function updateProfile(data) {
        try {

            const { payload, error } =  await ProfileService.updateProfileInfo(data);

            const { attributes, relations } = payload.data;

            profile.value = { ...attributes, ...relations };

            return { payload };

        } catch (_error) {
            return { error: _error };
        }
    }

    async function updatePassword(data) {
        try {

            const { payload, error } = await ProfileService.updatePassword(data);

            return { payload };

        } catch (_error) {
            return { error: _error };
        }
    }

    async function deleteAccount(data) {
        try {
            const { payload, error } = await ProfileService.deleteAccount(data);
            return { payload };

        } catch (error) {
            return { error: error };
        }
    }
    return {
        fetchProfile,
        updateProfile,
        updatePassword,
        deleteAccount,
        profile,
    };
};
