import {SettingsService} from '@/services/apiService';

const useSettingsStore = defineStore('settings', () => {
    const settings = ref([]);
    const isLoaded = ref(false);
    const error = ref(null);

    async function fetchSettings() {
        if (isLoaded.value) return;

        try {
            const {payload} = await SettingsService.getSettings();
            settings.value = payload.data;
            isLoaded.value = true;
        } catch (err) {
            handleError(err);
        }
    }

    async function updateSettings(data) {
        try {

            const {payload} = await SettingsService.updateSettings(data);

            return {payload};

        } catch (error) {
            handleError(error);
        }
    }

    function handleError(err) {
        error.value = err?.response?.data?.message || err.message || 'An error occurred';
        console.error('Error fetching settings:', error.value);
    }

    return {
        settings,
        isLoaded,
        error,
        fetchSettings,
        updateSettings,
    };
});

export {useSettingsStore};
