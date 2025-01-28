import axios from 'axios';
import { useAuthStore } from '@/stores/authStore';
import { useGlobalStore } from '@/stores/globalStore';
import { RouteNames } from '@/constants/routeNames';

const API_VERSION = import.meta.env.VITE_APP_API_V || '1.0';
const BASE_URL = import.meta.env.VITE_APP_BASE_URL;
const DEFAULT_PER_PAGE = import.meta.env.VITE_APP_PER_PAGE_DEFAULT || 10;

const DEFAULT_SORT_PARAMS = {
    sort_by: 'created_at',
    sort_dir: 'desc',
    per_page: DEFAULT_PER_PAGE,
};

const mergeSortParams = (params = {}) => ({
    ...DEFAULT_SORT_PARAMS,
    ...params,
});

const httpService = axios.create({
    baseURL: BASE_URL,
    timeout: 10000, // 10 seconds
});

// Request Interceptor
httpService.interceptors.request.use(
    (config) => {

        const authStore = useAuthStore();
        const globalStore = useGlobalStore();

        if (!config?.noGlobalLoading) {
            globalStore.actLoading(true);
        }

        const token = authStore.token;

        if (token) {
            config.headers['Authorization'] = token;
        }

        if (config.params) {
            config.params = mergeSortParams(config.params);
        }

        // Add headers
        config.headers = {
            ...config.headers,
            version: API_VERSION,
        };

        return config;
    },
    (error) => {
        console.error('Request Error:', error);
        return Promise.reject(error);
    }
);

// Response Interceptor
httpService.interceptors.response.use(
    async (response) => {

        const globalStore = useGlobalStore();

        await globalStore.actLoading?.(false);

        return response.data;
    },
    async (error) => {

        const authStore = useAuthStore();
        const globalStore = useGlobalStore();

        await globalStore.actLoading?.(false);

        const response = error.response;

        if (!response) {
            console.error('Network/Server error:', error);
            return Promise.reject({message: 'Network error. Please try again.'});
        }

        switch (response.status) {
            case 401:
                authStore.clearAuthData();
                break;
            case 403:
                window.location.replace('/' + RouteNames.FORBIDDEN_ACCESS);
                break;
            default:
                console.error(`Response Error: ${response.status}`, response.data?.message || error.message);
        }

        return Promise.reject(response.data || {message: 'An error occurred. Please try again.'});
    }
);

export {httpService};
