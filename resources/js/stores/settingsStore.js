import {SettingsService} from '@/services/apiService';

const useSettingsStore = defineStore('settings', () => {
    const settings = ref([]);
    const isLoaded = ref(false);
    const error = ref(null);

    async function fetchSettings() {
        if (isLoaded.value) return;

        try {
            const {data} = await SettingsService.getSettings();
            settings.value = data;
            isLoaded.value = true;
        } catch (error) {
            handleError(error);
        }
    }

    async function updateSettings(data) {
        try {

            const res = await SettingsService.updateSettings(data);

            return {res};

        } catch (error) {
            return {error: error}
        }
    }

    function handleError(err) {
        error.value = err.message || 'An error occurred';
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
