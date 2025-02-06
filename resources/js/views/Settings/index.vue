<script setup>
import { useSettingsStore } from '@/stores/settingsStore';
import { dataURLToFile, handleError } from '@/utils';
import { Cropper } from 'vue-advanced-cropper';
import { useCreateForm } from '@/hooks/useCreateForm';
import validationSchemas from '@/utils/validationSchemas';

const settingsStore = useSettingsStore();
const { settings } = storeToRefs(settingsStore);

const dialogVisible = ref(false);
const fileInput = ref(null);
const logoPreview = ref(settings.value.logo);
const faviconPreview = ref(settings.value.favicon);

const selectedImg = ref(null);
const croppedImage = ref(null);
const currentAvatarType = ref(null);
const settingsUpdated = ref(false);

const stencilProps = ref({
    aspectRatio: 1,
    movable: true,
    resizable: false
});

const initialValues = {
    name: '',
    logo: null,
    favicon: null,
};

const settingsForm = useCreateForm({
    initialValues,
    schema: validationSchemas.updateSettingsSchema,
});

const {
    form: { errors, values, submitForm, meta, resetForm, setErrors, setFieldValue },
    fields,
    getErrorMessage,
} = settingsForm;

onMounted(() => {
    setFieldValue('name', settings.value.name);
});

const onFileSelected = (event) => {
    const file = event.target.files[0];
    if (file) {
        selectedImg.value = URL.createObjectURL(file);
        dialogVisible.value = true;
    } else {
        selectedImg.value = null;
    }
};

const onCropChange = ({ canvas }) => {
    if (canvas) croppedImage.value = canvas.toDataURL();
};

const onUpload = () => {
    if (croppedImage.value) {
        const file = dataURLToFile(croppedImage.value, `${currentAvatarType.value}_photo.png`);
        if (file) {
            setFieldValue(currentAvatarType.value, file);
            if (currentAvatarType.value === 'logo') {
                logoPreview.value = croppedImage.value;
            } else if (currentAvatarType.value === 'favicon') {
                faviconPreview.value = croppedImage.value;
            }
        }
    }
    dialogVisible.value = false;
};


const triggerFileInput = (type) => {
    currentAvatarType.value = type;
    stencilProps.value.resizable = type === 'logo';
    stencilProps.value.aspectRatio = type !== 'logo' ? 1 : null;
    fileInput.value.click();
};

const resetCropper = () => {
    fileInput.value.value = null;
    selectedImg.value = null;
    dialogVisible.value = false;
};

async function handleSubmit() {


    if (!meta.value.valid) {
        submitForm();
        return;
    }

    const {res, error} = await settingsStore.updateSettings(values);


    if (error) {
        handleError(error, setErrors);
        return;
    }
    settingsUpdated.value = true;

    ElMessage.success({
        message: res?.message,
    });


}
const reloadPage = () => {
    window.location.reload();
};


</script>
<template>
    <div class="my-3"  v-if="settingsUpdated">
    <el-alert
        title="To see all changes applied correctly, please reload the page."
        type="warning"
        show-icon
        class="rounded-xl shadow-md  dark:bg-blue-900"
        @close="reloadPage"
        close-text="Reload" />
    </div>

    <el-card class="mb-2 bg-white border-b dark:bg-gray-800 dark:border-gray-700 p-6" shadow="hover">
        <input ref="fileInput" style="display: none" type="file" @change="onFileSelected" accept="image/*" />
        <el-form label-position="top" label-width="100px">
            <div class="grid grid-cols-2 gap-2 place-items-center">
                <div class="flex flex-col items-center gap-2">
                    <el-form-item
                        :error="getErrorMessage('logo')"
                        label="Logo">
                        <el-avatar
                            :size="150"
                            @click="() => triggerFileInput('logo')"
                            shape="square"
                            :src="logoPreview"
                            class="ring-2 ring-sky-300 dark:bg-gray-300 hover:cursor-pointer mt-2"
                        />
                    </el-form-item>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <el-form-item
                        :error="getErrorMessage('favicon')"
                        label="Favicon">
                        <el-avatar
                            :size="150"
                            @click="() => triggerFileInput('favicon')"
                            shape="square"
                            :src="faviconPreview"
                            class="ring-2 ring-sky-300 dark:bg-gray-300 hover:cursor-pointer mt-2"
                        />
                    </el-form-item>
                </div>
            </div>

        <el-form-item
            :error="getErrorMessage('name')"
            label="Name">
            <el-input
                v-model="fields.name.value.value"
                type="text"
                @blur="fields.name.handleBlur"/>
        </el-form-item>
        <div class="mt-6 text-right">
            <el-button
                type="primary"
                v-hasPermission="`update_system_settings`"
                @click="handleSubmit"
            >Update
            </el-button>
        </div>
        </el-form>
    </el-card>

    <el-dialog v-model="dialogVisible" width="500px">
        <el-card class="mt-4" shadow="never">
            <cropper
                v-if="selectedImg"
                :src="selectedImg"
                :stencil-props="stencilProps"
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
