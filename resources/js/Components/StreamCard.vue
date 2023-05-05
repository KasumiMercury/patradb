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
        class="relative box-border flex w-full select-text flex-col overflow-auto rounded-md"
    >
        <p
            class="mx-auto w-4/5 overflow-auto whitespace-normal break-all py-3 text-2xl"
        >
            {{ formatTime(item.scheduled_at) }}
        </p>
        <img
            v-if="item.video_id"
            :src="
                'https://img.youtube.com/vi/' +
                item.video_id +
                '/maxresdefault.jpg'
            "
            alt=""
            class="w-full drag-none"
        />
        <div class="mx-auto w-11/12 overflow-auto">
            <p class="pt-3 pb-6 title-display">{{ item.title }}</p>
        </div>
        <a
            v-if="item.video_id"
            :href="'https://www.youtube.com/watch?v=' + item.video_id"
            class="absolute top-0 left-0 h-full w-full"
        ></a>
    </div>
</template>
