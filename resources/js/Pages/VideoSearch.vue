<script setup>
import { Head, Link, useRemember, router } from "@inertiajs/vue3";
import { ref, onMounted, watch, computed, onUnmounted } from "vue";
import MemoryBack from "@/Components/MemoryBack.vue";
import Paginatebox from "@/Components/PaginateBox.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { ja } from "date-fns/locale";
import pkg from "lodash";
import dayjs from "dayjs";
import Modal from "../Components/Modal.vue";
const { isEmpty, transform } = pkg;
const props = defineProps({
    videoList: Object,
    query: Object,
});
console.log(props.videoList);
console.log(props.query);

const openModal = ref(false);
const openControll = ref(true);
const modalIndex = ref(0);

const copied = ref(false);

const queryInput = ref({
    search: "",
    required: "",
    excluded: "",
    order: null,
    by: null,
    since: null,
    until: null,
    page: "1",
});
const dateInput = ref({
    since: null,
    until: null,
});
const changed = ref(false);

const decodeFormat = (input) => {
    const day = dayjs(input);
    return day.format();
};

const inheritanceQuery = { ...queryInput.value, ...props.query };
queryInput.value = inheritanceQuery;
dateInput.value.since = decodeFormat(inheritanceQuery.since);
dateInput.value.until = decodeFormat(inheritanceQuery.until);

const mounted = ref(false);
onMounted(() => {
    mounted.value = true;
    // if props.query is not empty, set openControll to false
    if (Object.keys(props.query).length !== 0) {
        openControll.value = false;
    }
});

const format = (input) => {
    const day = dayjs(input);
    return day.format("M/D HH:00");
};
const queryFormat = (input) => {
    const day = dayjs(input);
    return day.format("YYYY-MM-DD");
};

const sendQuery = computed(() => {
    let removed = removeEmpty(queryInput.value);
    console.log(removed);
    if (removed.search) {
        removed.search = removed.search.replace(/　/g, " ");
    }
    if (removed.required) {
        removed.required = removed.required.replace(/　/g, " ");
    }
    if (removed.excluded) {
        removed.excluded = removed.excluded.replace(/　/g, " ");
    }
    return removed;
});
const removeEmpty = (arr) => {
    return transform(arr, function (result, value, key) {
        if (!isEmpty(value)) {
            if (typeof value === "object") {
                result[key] = removeEmpty(value);
            } else {
                result[key] = value;
            }
        }
    });
};
const orderDesk = () => {
    queryInput.value.order = "desc";
    queryInput.value.page = "1";
    router.visit(route("memory.videos"), {
        data: sendQuery.value,
    });
};
const orderAsc = () => {
    queryInput.value.order = "asc";
    queryInput.value.page = "1";
    router.visit(route("memory.videos"), {
        data: sendQuery.value,
    });
};
const byTitle = () => {
    if (queryInput.value.by == "title") {
        queryInput.value.by = null;
    } else {
        queryInput.value.by = "title";
    }
    queryInput.value.page = "1";
    router.visit(route("memory.videos"), {
        data: sendQuery.value,
    });
};
const bySchedule = () => {
    if (queryInput.value.by == "scheduled_at") {
        queryInput.value.by = null;
    } else {
        queryInput.value.by = "scheduled_at";
    }
    queryInput.value.page = "1";
    router.visit(route("memory.videos"), {
        data: sendQuery.value,
    });
};
const Send = () => {
    queryInput.value.page = "1";
    router.visit(route("memory.videos"), {
        data: sendQuery.value,
    });
};

const paginate = (page) => {
    queryInput.value.page = String(page);
    router.visit(route("memory.videos"), {
        data: sendQuery.value,
    });
};

const openOperateModal = (index) => {
    copied.value = false;
    modalIndex.value = index;
    openModal.value = true;
};

watch(
    () => dateInput.value.since,
    (value) => {
        if (value) {
            queryInput.value.since = queryFormat(value);
        } else {
            queryInput.value.since = null;
        }
        changed.value = true;
    }
);
watch(
    () => dateInput.value.until,
    (value) => {
        if (value) {
            queryInput.value.until = queryFormat(value);
        } else {
            queryInput.value.until = null;
        }
        changed.value = true;
    }
);

const targetUrl = computed(() => {
    const item = props.videoList.data[modalIndex.value];
    if (!item) return;
    if (item.start) {
        return "https://youtu.be/" + item.video_id + "?t=" + item.start + "s";
    } else {
        return "https://www.youtube.com/watch?v=" + item.video_id;
    }
});

const copyToClipboard = (text) => {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text);
    } else {
        const textarea = document.createElement("textarea");
        textarea.value = text;
        textarea.style.position = "fixed";
        document.body.appendChild(textarea);
        textarea.focus();
        textarea.select();
        document.execCommand("copy");
        document.body.removeChild(textarea);
    }
    copied.value = true;
};
</script>
<script>
import AppLayout from "../Layouts/AppLayout.vue";

export default {
    layout: AppLayout,
};
</script>
<style scoped>
/* add scale y from 1 to 0 transition to vue-transition group */
/* set transform-origin to top */
/* .v-enter-active,
.v-leave-active {
    transition: transform 0.3s ease-in-out;
    transform-origin: top;
}
.v-enter-from,
.v-leave-to {
    transform: scaleY(0);
} */
</style>
<template>
    <div>
        <Head>
            <title>VideoSearch</title>
        </Head>
        <Teleport to='[data-slot="header"]' v-if="mounted">
            <p class="text-xs font-semibold text-gray-800">
                <Link :href="route('memory.top')" class="underline"
                    >Memories</Link
                >
                / VideoSearch
            </p>
        </Teleport>
        <div id="controllBox">
            <div
                class="mx-auto mt-6 mb-6 w-full max-w-5xl px-2 text-ptr-dark-brown"
            >
                <div
                    class="h-fit rounded-md bg-ptr-light-pink px-2 py-2 shadow-md shadow-custom-shadow/50 md:px-6"
                >
                    <div
                        class="flex w-full cursor-pointer flex-row items-center justify-around px-4 py-2"
                        @click="openControll = !openControll"
                    >
                        <h3 class="text-lg">Search</h3>
                        <div class="ml-auto flex h-fit w-fit items-center">
                            <button>
                                <svg
                                    :class="openControll ? '' : 'rotate-180'"
                                    class="h-6 w-6"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 320 512"
                                >
                                    <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <path
                                        d="M182.6 137.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-9.2 9.2-11.9 22.9-6.9 34.9s16.6 19.8 29.6 19.8H288c12.9 0 24.6-7.8 29.6-19.8s2.2-25.7-6.9-34.9l-128-128z"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <transition>
                        <form @submit.prevent="Send()">
                            <div v-if="openControll">
                                <InputLabel
                                    for="searchWord"
                                    value="Search Word"
                                />
                                <TextInput
                                    v-model="queryInput.search"
                                    @change="changed = true"
                                    id="searchWord"
                                    class="w-full py-1 px-3 text-lg"
                                ></TextInput>
                                <div
                                    class="mt-2 mb-6 flex flex-col justify-center gap-1 md:flex-row"
                                >
                                    <div class="grow">
                                        <InputLabel
                                            for="required"
                                            value="Required"
                                        />
                                        <TextInput
                                            v-model="queryInput.required"
                                            @change="changed = true"
                                            id="required"
                                            class="w-full py-1 px-3 text-lg"
                                        ></TextInput>
                                    </div>
                                    <div class="grow">
                                        <InputLabel
                                            for="excluded"
                                            value="Excluded"
                                        />
                                        <TextInput
                                            v-model="queryInput.excluded"
                                            @change="changed = true"
                                            id="excluded"
                                            class="w-full py-1 px-3 text-lg"
                                        ></TextInput>
                                    </div>
                                </div>
                                <div class="mt-1 mb-2 mr-2 ml-auto w-fit">
                                    <!-- reset search words button -->
                                    <button
                                        type="button"
                                        class="rounded-md border border-ptr-main px-2 py-1"
                                        @click="
                                            queryInput.search = '';
                                            queryInput.required = '';
                                            queryInput.excluded = '';
                                            changed = true;
                                        "
                                    >
                                        Reset
                                    </button>
                                </div>
                                <div
                                    class="my-4 flex flex-row justify-center gap-1"
                                >
                                    <div class="flex flex-col items-start">
                                        <InputLabel for="since" value="Since" />
                                        <VueDatePicker
                                            class="max-w-xs"
                                            locale="jp"
                                            :format-locale="ja"
                                            timezone="Asia/Tokyo"
                                            v-model="dateInput.since"
                                            id="since"
                                            :enable-time-picker="false"
                                            auto-apply
                                        ></VueDatePicker>
                                    </div>
                                    <div class="flex flex-col items-start">
                                        <InputLabel for="until" value="Until" />
                                        <VueDatePicker
                                            class="max-w-xs"
                                            locale="jp"
                                            :format-locale="ja"
                                            timezone="Asia/Tokyo"
                                            v-model="dateInput.until"
                                            id="until"
                                            :enable-time-picker="false"
                                            auto-apply
                                        ></VueDatePicker>
                                    </div>
                                </div>
                                <div class="my-2 mr-2 ml-auto w-fit">
                                    <button
                                        type="submit"
                                        class="relative flex rounded-md bg-ptr-main px-4 py-2 text-white"
                                    >
                                        <span
                                            v-if="changed"
                                            class="absolute top-0 right-0 h-3 w-3 animate-ping rounded-full bg-ptr-white"
                                        ></span>
                                        <span class="relative inline-flex"
                                            >Search</span
                                        >
                                    </button>
                                </div>
                            </div>
                            <div
                                class="mb-3 mt-6 flex flex-row flex-wrap gap-2"
                            >
                                <div class="flex flex-row items-center gap-1">
                                    <p class="text-sm">OrderBy：</p>
                                    <button
                                        type="button"
                                        class="rounded-md px-2 py-1"
                                        @click="byTitle()"
                                        :class="
                                            queryInput.by === 'title'
                                                ? 'bg-ptr-dark-brown text-ptr-white'
                                                : 'border border-ptr-main'
                                        "
                                    >
                                        Title
                                    </button>
                                    <button
                                        type="button"
                                        class="rounded-md px-2 py-1"
                                        @click="bySchedule()"
                                        :class="
                                            queryInput.by === 'scheduled_at'
                                                ? 'bg-ptr-dark-brown text-ptr-white'
                                                : 'border border-ptr-main'
                                        "
                                    >
                                        ScheduleTime
                                    </button>
                                </div>
                                <div class="flex flex-row items-center gap-1">
                                    <p class="text-sm">OrderDirection：</p>
                                    <button
                                        type="button"
                                        class="rounded-md px-2 py-1"
                                        @click="orderAsc()"
                                        :class="
                                            queryInput.order == 'asc'
                                                ? 'bg-ptr-dark-brown text-ptr-white'
                                                : 'border border-ptr-main'
                                        "
                                    >
                                        <svg
                                            class="h-5 w-5"
                                            :class="
                                                queryInput.order == 'asc'
                                                    ? 'fill-ptr-white'
                                                    : 'fill-ptr-dark-brown'
                                            "
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 320 512"
                                        >
                                            <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                            <path
                                                d="M182.6 137.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-9.2 9.2-11.9 22.9-6.9 34.9s16.6 19.8 29.6 19.8H288c12.9 0 24.6-7.8 29.6-19.8s2.2-25.7-6.9-34.9l-128-128z"
                                            />
                                        </svg>
                                    </button>
                                    <button
                                        type="button"
                                        class="rounded-md px-2 py-1"
                                        @click="orderDesk()"
                                        :class="
                                            queryInput.order === 'desc' ||
                                            queryInput.order == null
                                                ? 'bg-ptr-dark-brown text-ptr-white'
                                                : 'border border-ptr-main'
                                        "
                                    >
                                        <svg
                                            class="h-5 w-5"
                                            :class="
                                                queryInput.order === 'desc' ||
                                                queryInput.order == null
                                                    ? 'fill-ptr-white'
                                                    : 'fill-ptr-dark-brown'
                                            "
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 320 512"
                                        >
                                            <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                            <path
                                                d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </transition>
                </div>
            </div>
            <div class="mx-auto flex w-full max-w-3xl flex-row justify-around">
                <Paginatebox
                    class="mt-2 mb-4"
                    @paginate="paginate"
                    :data="props.videoList"
                ></Paginatebox>
            </div>
        </div>
        <div
            class="grid w-full grid-cols-2 items-stretch gap-4 px-3 xl:gap-x-2 xl:gap-y-4 xl:px-12"
            id="displayBox"
            v-if="props.videoList.data.length > 0"
        >
            <div
                v-for="(item, index) in props.videoList.data"
                :key="index"
                class="relative flex h-full w-full cursor-pointer flex-col rounded-md bg-white xl:h-28 xl:flex-row"
                @click="openOperateModal(index)"
            >
                <img
                    class="rounded-l-md object-contain"
                    :src="
                        'https://img.youtube.com/vi/' +
                        item.video_id +
                        '/maxresdefault.jpg'
                    "
                    alt=""
                />
                <div
                    class="relative flex h-full w-full flex-col px-3 pt-2 pb-8"
                >
                    <p class="text-xs md:text-base">{{ item.title }}</p>
                    <p
                        class="absolute bottom-0 right-0 px-3 py-2 text-left text-xs md:text-sm"
                    >
                        {{ format(item.scheduled_at) }}
                    </p>
                </div>
            </div>
        </div>
        <MemoryBack
            class="fixed top-0 left-0 z-[-10] h-full w-full"
        ></MemoryBack>
        <Modal :show="openModal" @close="openModal = false">
            <!-- copy button and open the url button -->
            <div class="flex flex-col gap-4">
                <button
                    type="button"
                    @click="copyToClipboard(targetUrl)"
                    class="flex flex-row items-center justify-center gap-2 rounded-md bg-ptr-dark-brown px-4 py-2 text-ptr-white"
                >
                    <svg
                        class="fill-ptr-white"
                        xmlns="http://www.w3.org/2000/svg"
                        height="1em"
                        viewBox="0 0 512 512"
                    >
                        <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <path
                            d="M272 0H396.1c12.7 0 24.9 5.1 33.9 14.1l67.9 67.9c9 9 14.1 21.2 14.1 33.9V336c0 26.5-21.5 48-48 48H272c-26.5 0-48-21.5-48-48V48c0-26.5 21.5-48 48-48zM48 128H192v64H64V448H256V416h64v48c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V176c0-26.5 21.5-48 48-48z"
                        /></svg
                    >Copy
                </button>
                <a
                    :href="targetUrl"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex flex-row items-center justify-center gap-2 rounded-md bg-[#FF0000] px-4 py-2 text-ptr-white"
                    ><svg
                        class="fill-ptr-white"
                        xmlns="http://www.w3.org/2000/svg"
                        height="1em"
                        viewBox="0 0 576 512"
                    >
                        <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <path
                            d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"
                        />
                    </svg>
                    Open YouTube
                </a>
                <p v-if="copied" class="text-center">Copied!</p>
            </div>
        </Modal>
    </div>
</template>
