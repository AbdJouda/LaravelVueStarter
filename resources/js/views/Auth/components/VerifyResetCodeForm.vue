<script setup>
import VOtpInput from "vue3-otp-input";
import {AuthService} from '@/services/apiService';

const props = defineProps({
    username: {
        type: String,
        required: true,
    },
    code: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['verified', 'error']);

const route = useRoute();

const resetCode = ref('');
const otpInput = ref(null);
const codeError = ref(null);

const verifyCode = async () => {
    try {
        const {payload} = await AuthService.verifyResetCode({ username: props.username, code: props.code });
        emit('verified');
        ElMessage.success({
            message: payload.message,
        });
    } catch (error) {
        if (error?.http_status === 422) {
            codeError.value = error.payload.message;
        }
        emit('error', error);
    }
};

const handleOnComplete = async () => {
    await verifyCode();
};


const clearInput = () => {
    otpInput.value?.clearInput();
    codeError.value = null;
};


onMounted(() => {
    if (props.code) {
        otpInput.value?.fillInput(props.code);
        resetCode.value = props.code;
    }
});

</script>
<template>
        <div class="divide-y divide-gray-200">
            <div class="py-4 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
                    <v-otp-input
                        ref="otpInput"
                        v-model:value="resetCode"
                        :num-inputs="6"
                        :placeholder="['*', '*', '*', '*', '*', '*']"
                        :should-auto-focus="true"
                        :should-focus-order="true"
                        :input-classes="['otp-input',{'error': codeError}]"
                        inputType="number"
                        @on-complete="handleOnComplete"
                    />
                <div v-if="codeError" class="text-red-500 text-sm text-center mt-2">
                    {{ codeError }}
                </div>

                <div class="w-full flex justify-center">
                    <div class="space-x-6 flex justify-center">
                        <el-button size="large"
                                   type="primary"
                                   @click.prevent="clearInput">Clear Input
                        </el-button>
                    </div>
                </div>
            </div>
        </div>
</template>

<style scoped>

</style>
