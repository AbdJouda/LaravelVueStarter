import {ref, watch} from 'vue';
import _ from 'lodash';

const recordsPerPage = parseInt(import.meta.env.VITE_APP_PER_PAGE_DEFAULT, 10);

export function useTable(fetchDataCallback, pagination = null) {
    const currentPage = ref(0);
    const pageSize = ref(recordsPerPage || 10);
    const total = ref(0);
    const pageCount = ref(0);

    const sortBy = ref(null);
    const sortDirection = ref(null);
    const searchText = ref(null);

    onMounted(async () => {
        await fetchDataCallback();
    });

    const handleSortChange = async ({prop, order}) => {
        sortDirection.value = order === 'ascending' ? 'asc' : 'desc';
        await fetchDataCallback({
            sort_by: prop,
            sort_dir: sortDirection.value,
            per_page: pageSize.value,
        });
    };

    const handlePageChange = async (newPage) => {
        currentPage.value = newPage;
        await fetchDataCallback({
            page: currentPage.value,
        });
    };

    const handlePageSizeChange = async (newSize) => {
        pageSize.value = newSize;
        await fetchDataCallback({
            per_page: pageSize.value,
        });
    };

    const handleSearch = async () => {
        await fetchDataCallback({
            search: searchText.value,
            per_page: pageSize.value,
        });
    };

    const debouncedSearch = _.debounce(handleSearch, 200);

    watch(searchText, debouncedSearch);

    if (pagination) {
        watch(pagination, (newPagination) => {
            currentPage.value = newPagination.current_page_number;
            pageSize.value = newPagination.items_per_page;
            total.value = newPagination.total_items_count;
            pageCount.value = newPagination.last_page;
        });
    }
    const cleanup = () => {
        debouncedSearch.cancel();
    };

    onUnmounted(() => {
        cleanup();
    });

    return {
        sortBy,
        sortDirection,
        searchText,
        currentPage,
        pageSize,
        total,
        handleSortChange,
        handlePageChange,
        handlePageSizeChange,
        handleSearch,
    };
}
