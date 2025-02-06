<script setup>
import {useAuthStore} from '@/stores/authStore';
import {useProfile} from '../hooks/useProfile';
import {dataURLToFile, handleError} from '@/utils';
import {useCreateForm} from '@/hooks/useCreateForm';
import validationSchemas from '@/utils/validationSchemas';
import {CircleStencil, Cropper} from 'vue-advanced-cropper';

const {updateProfile} = useProfile();

const authStore = useAuthStore();
const {user} = storeToRefs(authStore);
const dialogVisible = ref(false)
const fileInput = ref(null);

const selectedFile = ref(null);
const selectedImg = ref(null);
const croppedImage = ref(null);
const tempCroppedImage = ref(null);

const initialValues = {
    name: '',
    email: '',
    phone: '',
    profile_photo: null,
};

const profileForm = useCreateForm({
    initialValues,
    schema: validationSchemas.updateProfileSchema,
});

const {
    form: {errors, values, submitForm, meta, resetForm, setErrors, setFieldValue},
    fields,
    getErrorMessage
} = profileForm;

onMounted(() => {
    initProfileForm();
});

function initProfileForm() {
    resetForm({values: {...user.value}});
}

const onFileSelected = (event) => {
    const file = event.target.files[0];
    if (file) {
        selectedFile.value = file;
        selectedImg.value = URL.createObjectURL(file);
        dialogVisible.value = true;
    } else {
        selectedFile.value = null;
        selectedImg.value = null;
    }
};

const onCropChange = ({canvas}) => {
    if (canvas) tempCroppedImage.value = canvas.toDataURL();
};

const onUpload = () => {
    if (tempCroppedImage.value) {
        croppedImage.value = tempCroppedImage.value;
        const file = dataURLToFile(croppedImage.value, 'profile_photo.png');
        if (file) {
            setFieldValue('profile_photo', file)
        }
    }
    dialogVisible.value = false;

};

const triggerFileInput = () => fileInput.value.click();

const resetCropper = () => {
    fileInput.value.value = null;
    selectedImg.value = null;
    dialogVisible.value = false;
};

const updateProfileDetails = async () => {

    if (!meta.value.valid) {
        submitForm();
        return;
    }

    const {res, error} = await updateProfile(values);


    if (error) {
        handleError(error, setErrors);
        return;
    }

    authStore.setUser(res.data);

    ElMessage.success({
        message: res?.message,
    });

    resetCropper();

};

</script>
<template>
    <div id="basic-info">
        <div class="flex-1">
            <el-card class="rounded-lg mb-3 dark:bg-gray-800 dark:border-gray-700" shadow="hover">
                <div class="flex items-center justify-between gap-4">
                    <!-- Avatar Section -->
                    <div class="flex items-center gap-4">
                        <el-avatar
                            :size="80"
                            @click="triggerFileInput"
                            :src="croppedImage || user?.profile_photo_url"
                            class="ring-2 ring-sky-300 dark:bg-gray-300 hover:cursor-pointer"
                        />
                        <div>
                            <p class="text-lg font-medium">{{ user.name }}</p>
                            <p class="text-gray-500">{{ user.email }}</p>
                        </div>
                    </div>

                    <!-- Button to Change Profile Picture -->
                    <el-button class="ml-auto" type="primary" @click="triggerFileInput">Change Picture</el-button>
                    <input ref="fileInput" style="display: none" type="file" @change="onFileSelected" accept="image/*" />
                </div>
            </el-card>

            <!-- Basic Info Form -->
            <el-card class="rounded-lg mb-3 dark:bg-gray-800 dark:border-gray-700" shadow="hover">
                <h2 class="text-lg font-medium mb-4">Basic Info</h2>

                <el-form label-position="top">
                    <div
                        class="flex flex-col items-center w-full space-x-0 space-y-2 sm:flex-row sm:space-x-4 sm:space-y-0 ">
                        <div class="w-full">
                            <el-form-item
                                :error="getErrorMessage('name')"
                                class="el-form-item--label-top"
                                label="Name">
                                <el-input
                                    v-model="fields.name.value.value"
                                    type="text"
                                    @blur="fields.name.handleBlur"/>
                            </el-form-item>
                            <el-form-item
                                :error="getErrorMessage('email')"
                                class="el-form-item--label-top"
                                label="Email">
                                <el-input
                                    v-model="fields.email.value.value"
                                    type="text"
                                    @blur="fields.email.handleBlur"/>
                            </el-form-item>
                            <el-form-item
                                :error="getErrorMessage('phone')"
                                class="el-form-item--label-top"
                                label="Phone">
                                <el-input
                                    v-model="fields.phone.value.value"
                                    type="text"
                                    @blur="fields.phone.handleBlur"/>
                            </el-form-item>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <el-button
                            type="primary"
                            @click="updateProfileDetails"
                        >Update
                        </el-button>
                    </div>
                </el-form>
            </el-card>
        </div>
    </div>
    <el-dialog v-model="dialogVisible" width="500px">
        <!-- File Input Trigger -->
        <!-- Cropper Section -->
        <el-card class="mt-4" shadow="never">
            <cropper
                v-if="selectedImg"
                :src="selectedImg"
                :stencil-component="CircleStencil"
                :stencil-props="{
                aspectRatio: 1,
                movable: true,
                resizable: false
	            }"
                class="cropper"
                @change="onCropChange"
            />
            <div v-else class="h-40 flex items-center justify-center text-gray-500">
                No image selected
            </div>
        </el-card>

        <template #footer>
            <el-button @click="resetCropper">Cancel</el-button>
            <el-button type="primary" @click="onUpload">Save</el-button>
        </template>
    </el-dialog>


</template>

<style scoped>
.el-button + .el-button {
    margin-left: 0 !important;
}
</style>
