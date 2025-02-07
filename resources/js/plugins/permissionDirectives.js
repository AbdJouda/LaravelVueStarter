import { usePermissionStore } from '@/stores/permissionStore';
import { watchEffect } from 'vue';

const hasPermissionDirective = {
    mounted(el, binding) {
        initializePermission(el, binding);
    },
    updated(el, binding) {
        handlePermissionUpdate(el, binding);
    },
    unmounted(el) {
        cleanupPermission(el);
    },
};


function initializePermission(el, binding) {
    if (typeof binding.value === 'undefined') {
        el.style.display = '';
        el.setAttribute('data-permission-status', 'granted');
        return;
    }

    el._permissionValue = binding.value;
    createPermissionWatcher(el);

}

function handlePermissionUpdate(el, binding) {
    if (binding.value !== el._permissionValue) {
        cleanupPermission(el);
        initializePermission(el, binding);
    }
}

function createPermissionWatcher(el) {
    const permissionStore = usePermissionStore();

    el._unwatchPermission = watchEffect(() => {
        try {
            const hasPermission = permissionStore.checkPermission(el._permissionValue);
            toggleElementVisibility(el, hasPermission);
        } catch (error) {
            console.error('[v-has-permission] Error checking permission:', error);
            toggleElementVisibility(el, false);
        }
    });
}

function toggleElementVisibility(el, isVisible) {
    el.style.display = isVisible ? '' : 'none';
    el.setAttribute('data-permission-status', isVisible ? 'granted' : 'denied');
}

function cleanupPermission(el) {
    if (el._unwatchPermission) {
        el._unwatchPermission();
        delete el._unwatchPermission;
        delete el._permissionValue;
        el.style.display = '';
        el.removeAttribute('data-permission-status');
    }
}

export { hasPermissionDirective };
