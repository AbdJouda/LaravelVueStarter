import { watch, ref } from 'vue';

const recordsPerPage = parseInt(import.meta.env.VITE_APP_PER_PAGE_DEFAULT, 10);

export function usePagination(pagination) {
    const currentPage = ref(0);
    const pageSize = ref(recordsPerPage || 10);
    const total = ref(0);
    const pageCount = ref(0);

    watch(pagination, (newPagination) => {
        currentPage.value = newPagination.current_page_number;
        pageSize.value = newPagination.items_per_page;
        total.value = newPagination.total_items_count;
        pageCount.value = newPagination.last_page;
    });

    return {
        currentPage,
        pageSize,
        total,
        recordsPerPage
    };
}
