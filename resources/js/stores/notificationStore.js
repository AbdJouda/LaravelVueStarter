import {useAuthStore} from './authStore';
import {notificationHandlers} from '@/utils';

const useNotificationsStore = defineStore('notifications', () => {
    const notificationsCount = ref(0);
    const notificationType = ref(null);
    let permissionType = 'default';
    const userStore = useAuthStore();
    const {user, token} = storeToRefs(userStore);


    function initWebsocket() {
        Echo.connector.options.auth.headers.Authorization = token.value;
        Echo.connector.options.auth.headers.Accept = "application/json";
        Echo.private(`App.Models.User.${user.value?.id}`)
            .notification((notification) => {
                notificationsCount.value++
                notificationType.value = notification.type
                const handler = notificationHandlers[notification.type];
                if (handler) {
                    try {
                        handler(notification);
                    } catch (error) {
                        console.error(`Error handling notification type "${notification.type}":`, error);
                    }
                }
                if (permissionType === 'granted') {
                    new Notification(notification.title, {
                        body: notification.body,
                    });
                }
            });
    }

    function resetCount() {
        notificationsCount.value = 0;
    }

    async function initBrowserNotification() {
        if (!('Notification' in window)) {
            permissionType = 'denied';
            return;
        }

        Notification.requestPermission().then((permission) => {
            permissionType = permission;
        });
    }

    return {
        notificationsCount,
        initBrowserNotification,
        initWebsocket,
        resetCount,
    };
});

export {useNotificationsStore};
