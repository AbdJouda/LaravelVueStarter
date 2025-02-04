<script setup>
import {useProfile} from '../hooks/useProfile';
import {handleValidationError} from '@/utils';
import {useAuthStore} from '@/stores/authStore';
import {RouteNames} from '@/constants/routeNames'

const {deleteAccount} = useProfile();
const authStore = useAuthStore();
const router = useRouter();

const openAccountDeletionPrompt = () => {
    // Open the prompt
    ElMessageBox.prompt(
        `
        <div>
            <p><strong>Are you sure you want to delete your account?</strong></p>
            <p>Once your account is deleted, all of its resources and data will be permanently deleted.</p>
            <p>Please enter your password below to confirm you would like to <strong>permanently delete your account</strong>.</p>
        </div>
        `,
        'Delete Account Confirmation',
        {
            type: 'warning',

            confirmButtonText: 'Delete Account',
            cancelButtonText: 'Cancel',
            dangerouslyUseHTMLString: true,
            inputType: 'password',
            beforeClose: async (action, instance, done) => {
                if (action === 'confirm') {
                    instance.confirmButtonLoading = true
                    const {inputValue: password} = instance;
                    const {res, error} = await deleteAccount({password: password});
                    if (error) {
                        instance.confirmButtonLoading = false;
                        handleValidationError(error)
                        return
                    }
                    ElMessage.success({
                        message: res?.message || 'Your account has been successfully deleted.',
                    });
                    await logout()
                    done()
                } else {
                    done()
                }
            },
        }
    );
};

const logout = async() => {
    authStore.clearAuthData();
    await router.push({name: RouteNames.LOGIN});
}
</script>
<template>
    <div id="delete-account" class="space-y-6">
        <div class="flex-1">
            <!-- Basic Info Form -->
            <el-card class="rounded-lg mb-3 dark:bg-gray-800 dark:border-gray-700" shadow="hover">
                <h2 class="text-lg font-medium mb-4">Permanently delete your account.</h2>
                <p class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Before
                    deleting your account, please download any data or information that you wish to retain.
                </p>
                <div class="flex justify-end">
                    <el-button
                        type="danger"
                        @click="openAccountDeletionPrompt"
                    >Delete Account
                    </el-button>
                </div>
            </el-card>
        </div>
    </div>
</template>

<script>
export default {
    name: "DeleteAccount"
}
</script>

<style scoped>

</style>
