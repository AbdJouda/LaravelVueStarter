
const useGlobalStore = defineStore('global', () => {
    const loading = ref(false);
    const localLoading = ref(0);

    async function actLoading(status) {
        if (status) {
            localLoading.value += 1;
        } else {
            localLoading.value = Math.max(localLoading.value - 1, 0);
        }

        loading.value = localLoading.value > 0;
    }


    return {
        loading,
        actLoading,
    }
})

export { useGlobalStore };
