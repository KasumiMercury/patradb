<script setup>
import { ref, onMounted } from "vue";
import { Link } from "@inertiajs/vue3";
import dayjs from "dayjs";
defineProps({
    item: Object,
});

const format = (input) => {
    const day = dayjs(input);
    return day.format("M/DD HH:mm");
};
const formatDay = (input) => {
    const day = dayjs(input);
    return day.format("M/DD");
};
const formatTime = (input) => {
    const day = dayjs(input);
    return day.format("HH:mm");
};
</script>
<template>
    <div
        class="card indicator relative box-border flex w-full select-text flex-col rounded-md"
        :class="{
            'border-2 border-ptr-main': item.status == 'live',
        }"
    >
        <span
            v-if="item.status == 'live'"
            class="badge-primary badge indicator-item animate-pulse text-white"
            >live
        </span>
        <figure v-if="item.video_id">
            <img
                :src="
                    'https://img.youtube.com/vi/' +
                    item.video_id +
                    '/maxresdefault.jpg'
                "
                alt=""
                class="w-full drag-none"
                width="1280"
                height="720"
            />
        </figure>
        <div class="card-body text-left">
            <h2 class="card-title">{{ format(item.scheduled_at) }}</h2>
            <p class="break-words pt-3 pb-6 title-display">
                {{ item.title }}
            </p>
            <div v-if="item.video_id" class="card-actions justify-end">
                <div class="badge-outline badge badge-lg gap-2">
                    <svg
                        class="h-3 w-3"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <path
                            d="M320 0c-17.7 0-32 14.3-32 32s14.3 32 32 32h82.7L201.4 265.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L448 109.3V192c0 17.7 14.3 32 32 32s32-14.3 32-32V32c0-17.7-14.3-32-32-32H320zM80 32C35.8 32 0 67.8 0 112V432c0 44.2 35.8 80 80 80H400c44.2 0 80-35.8 80-80V320c0-17.7-14.3-32-32-32s-32 14.3-32 32V432c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16H192c17.7 0 32-14.3 32-32s-14.3-32-32-32H80z"
                        />
                    </svg>
                    YouTube
                </div>
            </div>
        </div>
        <a
            v-if="item.video_id"
            :href="'https://www.youtube.com/watch?v=' + item.video_id"
            class="absolute top-0 left-0 h-full w-full"
        ></a>
        <div
            v-if="item.status == 'archived'"
            class="pointer-events-none absolute h-full w-full select-none rounded-md bg-ptr-dark-brown/50"
        >
            <div
                class="badge-secondary badge badge-lg absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 gap-2 text-white"
            >
                <svg
                    class="h-3 w-3 fill-white stroke-white"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"
                >
                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path
                        d="M320 0c-17.7 0-32 14.3-32 32s14.3 32 32 32h82.7L201.4 265.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L448 109.3V192c0 17.7 14.3 32 32 32s32-14.3 32-32V32c0-17.7-14.3-32-32-32H320zM80 32C35.8 32 0 67.8 0 112V432c0 44.2 35.8 80 80 80H400c44.2 0 80-35.8 80-80V320c0-17.7-14.3-32-32-32s-32 14.3-32 32V432c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16H192c17.7 0 32-14.3 32-32s-14.3-32-32-32H80z"
                    />
                </svg>
                Archived
            </div>
        </div>
    </div>
</template>
