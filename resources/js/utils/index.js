import dayjs from 'dayjs'
import { ElMessage } from 'element-plus';
import { usePermissionStore } from '@/stores/permissionStore';

export const formatDate = (date , format = 'hh:mm') => {
    return dayjs(date).format(format)
}

export const checkIsMobile = () => {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
}

export function handleValidationError(error, setErrors = null) {
    if (error?.meta?.http_status === 422) {
        if(setErrors)
            setErrors({ ...error.data });

        ElMessage.error({
            message: error.message || 'Validation failed.',
        });
    } else {
        ElMessage.error({
            message: error?.message || 'An error occurred.',
        });
    }
}
export function handleError(error) {
    if (error.message) {
        ElMessage.error({
            message: error.message,
        });
    }
    return {error: error};
}
export const notificationHandlers = {
    'user.role.updated': async () => {
        const permissionStore = usePermissionStore();
        await permissionStore.fetchRoles(true);
    },
};


export function dataURLToFile(dataURL, filename) {
    const arr = dataURL.split(','), mime = arr[0].match(/:(.*?);/)[1];
    const bstr = atob(arr[1]);
    let n = bstr.length;
    const u8arr = new Uint8Array(n);

    while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
    }

    return new File([u8arr], filename, { type: mime });
}
