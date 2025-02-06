import {RouteNames} from '@/constants/routeNames'


const routes = [
    {
        path: '/',
        name: RouteNames.LOGIN,
        component: () => import('@/views/Auth/Login.vue'),
        meta: {
            requiresAuth: false,
        },
    },
    {
        path: '/' + RouteNames.FORGOT_PASSWORD,
        name: RouteNames.FORGOT_PASSWORD,
        component: () => import('@/views/Auth/ForgotPassword.vue'),
        meta: {
            requiresAuth: false,
        },
    },
    {
        path: '/' + RouteNames.RESET_PASSWORD,
        name: RouteNames.RESET_PASSWORD,
        component: () => import('@/views/Auth/ResetPassword.vue'),
        meta: {
            requiresAuth: false,
        },
    },
    {
        path: '/' + RouteNames.PROFILE,
        name: RouteNames.PROFILE,
        component: () => import('@/views/Profile/index.vue'),
        meta: {
            title: 'Profile',
            requiresAuth: true,
            parentPath: RouteNames.DASHBOARD
        },
    },
    {
        path: '/' + RouteNames.DASHBOARD,
        name: RouteNames.DASHBOARD,
        component: () => import('@/views/Dashboard/index.vue'),
        meta: {
            title: 'Dashboard',
            requiresAuth: true,
        },
    },
    {
        path: '/users',
        component: () => import('@/views/ManageUsers/index.vue'),
        meta: {
            title: 'Users',
            requiresAuth: true,
            parentPath: RouteNames.DASHBOARD,
        },
        children: [
            {
                path: RouteNames.LIST_USERS,
                name: RouteNames.LIST_USERS,
                component: () => import('@/views/ManageUsers/UserList.vue'),
                meta: {
                    title: 'Users List',
                    parentPath: RouteNames.DASHBOARD,
                    permissions: 'view_users',
                },
            },
            {
                path: RouteNames.ADD_USER,
                name: RouteNames.ADD_USER,
                component: () => import('@/views/ManageUsers/UserForm.vue'),
                meta: {
                    title: 'Add User',
                    parentPath: RouteNames.LIST_USERS,
                    permissions: 'add_users',
                },
            },
            {
                path: RouteNames.EDIT_USER + '/:id',
                component: () => import('@/views/ManageUsers/UserForm.vue'),
                name: RouteNames.EDIT_USER,
                meta: {
                    title: 'Edit User',
                    parentPath: RouteNames.LIST_USERS,
                    permissions: 'edit_users',
                },
            },
            {
                path: RouteNames.LIST_ROLES,
                name: RouteNames.LIST_ROLES,
                component: () => import('@/views/ManageUsers/RoleList.vue'),
                meta: {
                    title: 'Roles List',
                    parentPath: RouteNames.DASHBOARD,
                    permissions: 'view_roles',
                },
            },
            {
                path: RouteNames.ADD_ROLE,
                name: RouteNames.ADD_ROLE,
                component: () => import('@/views/ManageUsers/RoleForm.vue'),
                meta: {
                    title: 'Add Role',
                    parentPath: RouteNames.LIST_ROLES,
                    permissions: 'add_roles',
                },
            },
            {
                path: RouteNames.EDIT_ROLE + '/:id',
                name: RouteNames.EDIT_ROLE,
                component: () => import('@/views/ManageUsers/RoleForm.vue'),
                meta: {
                    title: 'Edit Role',
                    parentPath: RouteNames.LIST_ROLES,
                    permissions: 'edit_roles',

                },
            },

        ],
    },
    {
        path: '/' + RouteNames.SETTINGS,
        name: RouteNames.SETTINGS,
        component: () => import('@/views/Settings/index.vue'),
        meta: {
            title: 'App Settings',
            requiresAuth: true,
            parentPath: RouteNames.DASHBOARD,
            permissions: 'view_system_settings',
        },
    },
    {
        path: '/' + RouteNames.TODO_LIST,
        name: RouteNames.TODO_LIST,
        component: () => import('@/views/Todo/index.vue'),
        meta: {
            title: 'Todo List',
            requiresAuth: true,
            parentPath: RouteNames.DASHBOARD,
        },
    },
    {
        path: '/' + RouteNames.FORBIDDEN_ACCESS,
        name: RouteNames.FORBIDDEN_ACCESS,
        component: () => import('@/views/Error/403.vue'),
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: '/:not(.*)',
        redirect: '/',
    },
]

export default routes
