import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/authStore';
import { usePermissionStore } from '@/stores/permissionStore';
import { RouteNames } from '@/constants/routeNames';
import routes from './routes';
import NProgress from 'nprogress';

NProgress.configure({ showSpinner: false, speed: 500, minimum: 0.1 });

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    NProgress.start();

    const authStore = useAuthStore();
    const { user } = storeToRefs(authStore);
    const isAuthenticated = user.value;
    const requiresAuth = to.meta.requiresAuth;

    const permissionStore = usePermissionStore();
    const requiredPermission = to.meta.permissions;

    if (isAuthenticated && !requiresAuth) {
        return next({ name: RouteNames.DASHBOARD });
    }

    if (requiresAuth && !isAuthenticated) {
        return next({ name: RouteNames.LOGIN });
    }

    if (isAuthenticated && !permissionStore.hasLoadedPermissions) {
        await permissionStore.initializeRoles();
    }


    if (isAuthenticated && requiredPermission && !permissionStore.checkPermission(requiredPermission)) {
        return next({ name: RouteNames.FORBIDDEN_ACCESS });
    }


    next();
});


router.afterEach(() => {
    NProgress.done();
});

export default router;
