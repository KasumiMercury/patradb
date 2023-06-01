<script setup>
import { ref, onMounted } from "vue";
import { Link } from "@inertiajs/vue3";
import dayjs from "dayjs";
defineProps({
    item: Object,
});

const format = (input) => {
    const day = dayjs(input);
    return day.format("MM/DD HH:mm");
};
const formatDay = (input) => {
    const day = dayjs(input);
    return day.format("M/DD");
};
const formatTime = (input) => {
    const day = dayjs(input);
    return day.format("HH:mm");
};

const jumpToYoutube = (video_id) => {
    window.open("https://www.youtube.com/watch?v=" + video_id, "_blank");
};
</script>
<template>
    <div class="h-full py-2 px-3">
        <div
            class="card relative h-full bg-base-100 shadow-sm shadow-custom-shadow/50"
        >
            <figure>
                <img
                    :src="
                        'https://img.youtube.com/vi/' +
                        item.video_id +
                        '/maxresdefault.jpg'
                    "
                    alt="youtube thumbnail image for {{ item.title }}"
                    class="w-full cursor-pointer drag-none"
                    width="1280"
                    height="720"
                    @click="jumpToYoutube(item.video_id)"
                />
            </figure>
            <div class="card-body cursor-grab text-left">
                <h2 class="card-title">{{ format(item.scheduled_at) }}</h2>
                <p class="break-words pt-3 pb-6 title-display">
                    {{ item.title }}
                </p>
                <div class="card-actions justify-end">
                    <a
                        class="badge-outline badge badge-lg gap-2"
                        :href="
                            'https://www.youtube.com/watch?v=' + item.video_id
                        "
                        target="_blank"
                        rel="noopener"
                    >
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
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
