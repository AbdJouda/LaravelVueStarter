const useSidebarStore = defineStore('sidebar', () => {
    const isSidebarOpen = ref(false)
    const selected = useStorage('selected')
    const page = useStorage('page')

    function toggleSidebar() {
        isSidebarOpen.value = !isSidebarOpen.value
    }

    return { isSidebarOpen, toggleSidebar, selected, page};

})

export { useSidebarStore };
