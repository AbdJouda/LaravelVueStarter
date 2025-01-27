import {usePermissionStore} from '@/stores/permissionStore';
import {watchEffect} from 'vue';

const hasPermissionDirective = {
    mounted(el, binding) {
        if (!binding.value) return;

        const permissionStore = usePermissionStore();

        el._unwatchPermission = watchEffect(() => {
            const hasPermission = permissionStore.checkPermission(binding.value);

            if (hasPermission) {
                el.style.display = '';
            } else {
                el.style.display = 'none';
            }
        });
    },
    unmounted(el) {
        if (el._unwatchPermission) {
            el._unwatchPermission();
            delete el._unwatchPermission;
        }
    },
};

export { hasPermissionDirective };
