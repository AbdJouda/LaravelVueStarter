<script setup>
import { useSidebarStore } from '@/stores/sidebarStore';

const router = useRouter();
const route = useRoute();
const sidebarStore = useSidebarStore();

const handleItemClick = () => {
    const allRoutes = router.getRoutes();

    const getParentRoute = (parentPath) =>
        allRoutes.find((route) => route.name === parentPath);

    const parentPath = route.meta?.parentPath;
    if (!parentPath) return;

    const parentRoute = getParentRoute(parentPath);

    if (!parentRoute) {
        console.warn('Parent route not found!');
        return;
    }

    router.push(parentRoute);

};

const formatParentPath = (path) => path.replace(/-/g, ' ');


</script>

<template>
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-semibold text-black dark:text-white">
            {{ route.meta.title }}
        </h2>

        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <el-link
                        :underline="false"
                        class="text-sm capitalize text-slate-50 hover:text-white font-semibold"
                        href="javascript:void(0)"
                        @click="handleItemClick">
                        {{ route.meta.parentPath ? formatParentPath(route.meta.parentPath) + ' /' : '' }}
                    </el-link>
                </li>
                <li class="font-medium text-primary dark:text-white">{{ route.meta.title }}</li>
            </ol>
        </nav>
    </div>
</template>
