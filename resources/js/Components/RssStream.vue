<script setup>
import { ref, onMounted } from "vue";
import RssCard from "./RssCard.vue";
defineProps({
    data: Object,
});

const rssWrapper = ref(null);
const scrollPos = ref(0);
const scrollRange = ref(0);
const wrapperWidth = ref(0);

const scrollNext = () => {
    if (rssWrapper.value) {
        rssWrapper.value.scrollBy({
            left: rssWrapper.value.clientWidth,
            behavior: "smooth",
        });
    }
};
const scrollPrev = () => {
    if (rssWrapper.value) {
        rssWrapper.value.scrollBy({
            left: -rssWrapper.value.clientWidth,
            behavior: "smooth",
        });
    }
};
const getScrollPos = () => {
    scrollPos.value = rssWrapper.value.scrollLeft;
    scrollRange.value =
        rssWrapper.value.scrollWidth - rssWrapper.value.clientWidth;
};
const getWrapperWidth = () => {
    wrapperWidth.value = rssWrapper.value.clientWidth;
};

onMounted(() => {
    getWrapperWidth();
});

defineExpose({
    getWrapperWidth,
});
</script>
<template>
    <div
        class="relative w-full rounded-t-lg rounded-bl-lg rounded-br-3xl bg-ptr-white px-4 pb-4 before:absolute before:top-2 before:left-2 before:-z-10 before:h-full before:w-full before:rounded-t-lg before:rounded-bl-lg before:rounded-br-3xl before:bg-ptr-dark-brown"
    >
        <div class="w-full select-none text-ptr-dark-brown">
            <h1
                class="flex items-center px-6 pt-6 pb-3 text-2xl md:px-12 md:pt-12 md:pb-6"
            >
                <svg
                    fill="none"
                    stroke-width="1.5"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true"
                    class="mr-2 h-6 w-6 stroke-ptr-dark-brown"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9.348 14.651a3.75 3.75 0 010-5.303m5.304 0a3.75 3.75 0 010 5.303m-7.425 2.122a6.75 6.75 0 010-9.546m9.546 0a6.75 6.75 0 010 9.546M5.106 18.894c-3.808-3.808-3.808-9.98 0-13.789m13.788 0c3.808 3.808 3.808 9.981 0 13.79M12 12h.008v.007H12V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"
                    ></path>
                </svg>
                Upcoming Stream
            </h1>
        </div>
        <div class="relative">
            <div
                class="scrolling-touch relative box-border flex w-full snap-x scroll-p-4 flex-row items-stretch gap-2 overflow-x-scroll py-6 px-6 lg:px-12"
                ref="rssWrapper"
                @scroll="getScrollPos"
            >
                <div
                    v-for="(item, index) in data"
                    :key="index"
                    class="shrink-0 basis-full snap-start lg:basis-1/2 lg:pr-4"
                >
                    <RssCard
                        :item="item"
                        class="h-full rounded-md shadow-sm shadow-custom-shadow"
                    ></RssCard>
                </div>
            </div>
            <button
                v-if="scrollPos > wrapperWidth / 3"
                class="absolute top-1/2 left-0 -translate-y-1/2 -translate-x-1/2 rounded-full bg-ptr-main p-2 text-ptr-white shadow-md shadow-ptr-light-pink"
                @click="scrollPrev"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 256 512"
                    class="h-6 w-6 fill-ptr-white"
                >
                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path
                        d="M9.4 278.6c-12.5-12.5-12.5-32.8 0-45.3l128-128c9.2-9.2 22.9-11.9 34.9-6.9s19.8 16.6 19.8 29.6l0 256c0 12.9-7.8 24.6-19.8 29.6s-25.7 2.2-34.9-6.9l-128-128z"
                    />
                </svg>
            </button>
            <button
                v-if="scrollPos < scrollRange - wrapperWidth / 3"
                class="absolute top-1/2 right-0 -translate-y-1/2 translate-x-1/2 rounded-full bg-ptr-main p-2 text-ptr-white shadow-md shadow-ptr-light-pink"
                @click="scrollNext"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 256 512"
                    class="h-6 w-6 fill-ptr-white"
                >
                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path
                        d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"
                    />
                </svg>
            </button>
        </div>
    </div>
</template>
