<script setup>
import {useAuthStore} from '@/stores/authStore';
import {useNotificationsStore} from '@/stores/notificationStore';
import {ProfileService} from '@/services/apiService';

const authStore = useAuthStore();
const notificationStore = useNotificationsStore();
const {user} = storeToRefs(authStore);
const {notificationsCount} = storeToRefs(notificationStore);

const dropDownContainer = ref(false);
const scrollContainer = ref(null);
const dropdownOpen = ref(false);
const notifications = ref([]);
const pagination = ref(null);
const loading = ref({fetch: false, scroll: false});
const noMoreData = ref(false);

onMounted(() => {
    if (user.value) {
        setupWebsocketAndNotifications();
        notificationsCount.value += user.value.has_new_notifications;
    }
});

onClickOutside(dropDownContainer, () => {
    dropdownOpen.value = false;
});


function setupWebsocketAndNotifications() {
    if (user.value) {
        notificationStore.initWebsocket();
        notificationStore.initBrowserNotification();
    }
}

async function fetchNotifications(queryObj = {}, preventLoading = false) {
    if (!preventLoading) loading.value.fetch = true;

    try {
        const {payload} = await ProfileService.getNotifications({
            ...queryObj, per_page: 8
        });

        const res = payload.data.map((el) => ({
            ...el.attributes,
        }));

        notifications.value = queryObj.page ? [...notifications.value, ...res] : res;
        pagination.value = payload.meta;

        if (!pagination.value.next_page_url) noMoreData.value = true;
    } catch (error) {
        console.error('Failed to fetch notifications:', error);
        notifications.value = [];
    } finally {
        loading.value.fetch = false;
        loading.value.scroll = false;
    }
}


async function loadMoreNotifications() {
    if (pagination.value?.next_page_url && !loading.value.scroll) {
        loading.value.scroll = true;

        const nextPage = pagination.value.current_page_number + 1;
        await fetchNotifications({page: nextPage}, true);
    }
}

function toggleDropdown() {
    dropdownOpen.value = !dropdownOpen.value;

    if (dropdownOpen.value) {
        if (!notifications.value.length || notificationsCount.value) {
            fetchNotifications().then(() => {
                notificationStore.resetCount();
                user.value.has_new_notifications = false;
                authStore.setUser(user.value);
            })
        }
    }
}

function handleScroll() {
    const container = scrollContainer.value.wrapRef;

    const isAtBottom =
        container.scrollTop + container.clientHeight >= container.scrollHeight - 1;

    if (isAtBottom && !loading.value.scroll && !noMoreData.value) {
        loadMoreNotifications();
    }
}

</script>

<template>
    <li class="relative" ref="dropDownContainer">
        <a
            class="relative flex h-8.5 w-8.5 items-center justify-center rounded-full border-[0.5px] border-stroke bg-gray hover:text-primary dark:border-strokedark dark:bg-meta-4 dark:text-white"
            href="javascript:void(0)"
            @click.prevent="toggleDropdown"
        >
      <span
          :class="!notificationsCount && 'hidden'"
          class="absolute -top-0.5 right-0 z-1 h-2 w-2 rounded-full bg-meta-1"
      >
        <span
            class="absolute -z-1 inline-flex h-full w-full animate-ping rounded-full bg-meta-1 opacity-75"
        ></span>
      </span>
            <font-awesome-icon :icon="['fas', 'bell']"/>
        </a>

        <!-- Dropdown Start -->
        <div
            v-show="dropdownOpen"
            class="absolute -right-27 mt-2.5 flex h-90 w-75 flex-col rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark sm:right-0 sm:w-80"
        >
            <el-scrollbar
                ref="scrollContainer"
                @scroll="handleScroll">
                <div class="px-4.5 py-3">
                    <h5 class="text-sm font-medium text-bodydark2">Notification</h5>
                </div>

                <ul class="flex h-auto flex-col overflow-y-auto">
                    <li v-if="!loading.fetch && notifications.length === 0" class="flex justify-center items-center text-primary">
                        <el-empty :image-size="100" />
                    </li>
                    <template v-for="(item, index) in notifications" :key="index">
                        <li
                            :class="item.new ? 'bg-primary-light border-primary text-primary' : ''"
                        >
                            <router-link
                                :to="item.data.title"
                                class="flex flex-col gap-2.5 border-t border-stroke px-4.5 py-3 hover:bg-gray-2 dark:border-strokedark dark:hover:bg-meta-4"
                            >
                                <p class="text-sm">
                                    <span class="text-black dark:text-white">{{ item.data.title }}</span>
                                    {{ item.data.body }}
                                </p>

                                <div class="flex justify-between text-xs">
                                    <span>{{ item.date.time['12_hour_format'] }}</span>
                                    <span>{{ item.date.date.uk_string }}</span>
                                </div>
                            </router-link>
                        </li>
                    </template>
                </ul>
            </el-scrollbar>

            <div v-if="loading.fetch" class="mt-5 flex justify-center items-center text-primary">
               <font-awesome-icon :icon="['fas', 'spinner']" class="text-sm"/>
            </div>
        </div>
        <!-- Dropdown End -->
    </li>
</template>
