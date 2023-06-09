<script setup>
import { ref, watch, onMounted } from "vue";
import RssCard from "./RssCard.vue";
import "vue3-carousel/dist/carousel.css";
import { Carousel, Slide, Pagination, Navigation } from "vue3-carousel";
import { Link, usePage } from "@inertiajs/vue3";
import Modal from "./Modal.vue";
const props = defineProps({
    data: Object,
});
let settings = {
    snapAlign: "center",
    transition: 500,
};
let breakpoints = {
    768: {
        itemsToShow: 1.5,
        snapAlign: "center",
    },
};
if (Object.keys(props.data).length < 2) {
    settings = {
        snapAlign: "center",
    };
}
const emits = defineEmits(["openStreamNotionModal"]);

const openModal = () => {
    emits("openStreamNotionModal");
};
</script>
<style scoped>
.carousel__slide--prev,
.carousel__slide--next {
    opacity: 0.7;
    transform: scale(0.8);
}
.carousel__slide--sliding {
    transition: 0.5s;
}
.carousel__next,
.carousel__prev {
    border: 1px solid var(--vc-clr-primary);
}
section ::v-deep(button) {
    --vc-clr-primary: #d8346e;
    --vc-clr-secondary: #ff99cd;
    --vc-clr-white: #fffafb;
    --vc-clr-dark: #2d2a2d;

    --vc-nav-color: var(--vc-clr-primary);
    --vc-nav-color-hover: var(--vc-clr-secondary);
    --vc-nav-background: var(--vc-clr-white);

    --vc-pgn-background-color: var(--vc-clr-secondary);
    --vc-pgn-active-color: var(--vc-clr-primary);
}
</style>
<!-- <style deep>
:root {
    --vc-clr-primary: #d8346e;
    --vc-clr-secondary: #ff99cd;
    --vc-clr-white: #fffafb;
    --vc-clr-dark: #2d2a2d;

    --vc-nav-color: var(--vc-clr-primary);
    --vc-nav-color-hover: var(--vc-clr-secondary);
    --vc-nav-background: var(--vc-clr-white);

    --vc-pgn-background-color: var(--vc-clr-secondary);
    --vc-pgn-active-color: var(--vc-clr-primary);
}
</style> -->
<template>
    <div
        class="relative w-full rounded-t-lg rounded-br-lg rounded-bl-3xl bg-ptr-white px-4 pb-4 before:absolute before:top-2 before:left-2 before:-z-10 before:h-full before:w-full before:rounded-t-lg before:rounded-br-lg before:rounded-bl-3xl before:bg-ptr-dark-brown"
    >
        <div
            class="flex w-full flex-col items-center pt-6 pb-3 text-ptr-dark-brown md:flex-row md:pt-12 md:pb-6"
        >
            <h1 class="flex select-none items-center px-6 text-2xl md:px-12">
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
            <div class="my-2 ml-auto mr-2 w-fit md:my-0">
                <button
                    class="btn-primary btn-md btn text-white"
                    @click="openModal"
                >
                    通知設定
                </button>
            </div>
        </div>
        <div v-if="Object.keys(props.data).length > 0" class="relative">
            <Carousel
                v-bind="settings"
                :breakpoints="breakpoints"
                :wrap-around="Object.keys(props.data).length > 1"
            >
                <Slide v-for="(item, index) in props.data" :key="index">
                    <RssCard :item="item" class="h-full"></RssCard>
                </Slide>

                <template #addons>
                    <Navigation />
                    <Pagination />
                </template>
            </Carousel>
        </div>
        <div v-else>
            <p class="my-5 text-center text-lg text-ptr-main">Coming…</p>
        </div>
    </div>
</template>
