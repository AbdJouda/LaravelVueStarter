import {ProfileService} from '@/services/apiService';
import { handleError } from '@/utils';

export const useProfile = () => {
    const profile = ref(null);



    async function fetchProfile() {
        try {
            const res = await ProfileService.getProfile();

            profile.value = res.data;

        } catch (error) {
            handleError(error);
        }
    }


    async function updateProfile(data) {
        try {

            const res = await ProfileService.updateProfileInfo(data);

            profile.value = res.data;

            return { res };

        } catch (error) {
            return { error: error };
        }
    }

    async function updatePassword(payload) {
        try {

            const res = await ProfileService.updatePassword(payload);

            return { res };

        } catch (error) {
            return { error: error };
        }
    }

    async function deleteAccount(data) {
        try {
            const res = await ProfileService.deleteAccount(data);

            return { res };

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
