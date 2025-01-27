import './bootstrap';
import App from './App.vue'
import { createPinia } from 'pinia';
import ElementPlus from 'element-plus'
import { library } from '@fortawesome/fontawesome-svg-core'
import { fas } from '@fortawesome/free-solid-svg-icons'
import { fab } from '@fortawesome/free-brands-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import router from './router';
import vue3GoogleLogin from 'vue3-google-login'
import VueAppleLogin from 'vue-apple-login';
import { useAuthStore } from '@/stores/authStore';
import { useSettingsStore } from '@/stores/settingsStore';
import { hasPermissionDirective } from '@/plugins/permissionDirectives';


const createNewApp = async () => {
    const app = createApp({
        render: () => h(App),
    })
    library.add(fas, fab)
    app.component('font-awesome-icon', FontAwesomeIcon)
    app.use(router)
    app.use(ElementPlus)
    app.use(createPinia())
    app.directive('hasPermission', hasPermissionDirective);

    const authStore = useAuthStore();
    await authStore.initializeAuthentication();

    const settingsStore = useSettingsStore();
    if (!settingsStore.isLoaded) {
        await settingsStore.fetchSettings();
    }

    app.use(vue3GoogleLogin, {
        clientId: import.meta.env.VITE_GOOGLE_CLIENT_ID
    })

    app.use(VueAppleLogin, {
        clientId: import.meta.env.VITE_SIGN_IN_WITH_APPLE_CLIENT_ID,
        scope: 'name email',
        redirectURI: import.meta.env.VITE_APP_BASE_URL,
        state: Date.now().toString(),
        usePopup: true,
    });

        app.mount('#app')
}

const initApp = async () => {
    createNewApp()
}

initApp().then(() => {
    // initialized
})
