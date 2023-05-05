<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
const isCalendarOpen = ref(false);
const dayOfWeek = ref([]);
dayOfWeek.value = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
defineProps({
    monthData: Object,
    otherData: Object,
});
</script>

<style scoped>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.2s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
<template>
    <div
        class="relative rounded-t-lg bg-ptr-white px-4 pb-4 before:absolute before:top-2 before:-z-10 before:h-full before:w-full before:rounded-t-lg before:bg-ptr-pink"
    >
        <div class="w-full select-none text-ptr-dark-brown">
            <h1
                class="flex items-center px-6 pt-6 pb-3 text-2xl md:px-12 md:pt-12 md:pb-6"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 448 512"
                    class="mr-2 h-5 w-5 fill-ptr-dark-brown"
                >
                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path
                        d="M96 32V64H48C21.5 64 0 85.5 0 112v48H448V112c0-26.5-21.5-48-48-48H352V32c0-17.7-14.3-32-32-32s-32 14.3-32 32V64H160V32c0-17.7-14.3-32-32-32S96 14.3 96 32zM448 192H0V464c0 26.5 21.5 48 48 48H400c26.5 0 48-21.5 48-48V192z"
                    />
                </svg>
                All schedule
            </h1>
        </div>
        <div class="h-64 w-full"></div>
        <div class="ml-auto mt-4 mr-2 w-fit">
            <Link
                as="button"
                :href="route('create.schedule')"
                class="btn-secondary btn-sm btn text-white"
            >
                スケジュール登録
            </Link>
        </div>
    </div>
</template>
