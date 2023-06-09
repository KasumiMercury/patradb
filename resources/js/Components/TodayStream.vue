<script setup>
import { ref, onMounted, onUnmounted, computed, watch } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import StreamCard from "./StreamCard.vue";
import Modal from "./Modal.vue";
const props = defineProps({
    today: Object,
    tomorrow: Object,
});
const emits = defineEmits(["openStreamNotionModal"]);

const openModal = () => {
    emits("openStreamNotionModal");
};
</script>
<template>
    <div
        class="relative rounded-t-lg rounded-bl-lg rounded-br-3xl bg-ptr-white px-4 pb-4 before:absolute before:top-2 before:left-2 before:-z-10 before:h-full before:w-full before:rounded-t-lg before:rounded-br-3xl before:rounded-bl-lg before:bg-yellow-500"
    >
        <div
            class="flex w-full flex-col items-center pt-6 pb-3 text-ptr-dark-brown md:flex-row md:pt-12 md:pb-6"
        >
            <h1 class="flex select-none items-center text-2xl md:px-12">
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
                Streaming Schedules
            </h1>
            <div class="my-2 ml-auto mr-2 w-fit md:my-0">
                <button
                    class="btn-primary btn text-white btn-md"
                    @click="openModal"
                >
                    通知設定
                </button>
            </div>
        </div>
        <div class="w-full select-none text-ptr-dark-brown">
            <h1
                class="flex items-center px-6 pt-6 pb-1 text-xl lg:px-12 lg:pt-12"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"
                    class="mr-2 h-5 w-5 fill-ptr-dark-brown"
                >
                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path
                        d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zM188.3 147.1c7.6-4.2 16.8-4.1 24.3 .5l144 88c7.1 4.4 11.5 12.1 11.5 20.5s-4.4 16.1-11.5 20.5l-144 88c-7.4 4.5-16.7 4.7-24.3 .5s-12.3-12.2-12.3-20.9V168c0-8.7 4.7-16.7 12.3-20.9z"
                    />
                </svg>
                Today's
            </h1>
        </div>
        <div class="py-3 px-2 xl:py-6 xl:px-4">
            <p
                class="text-center text-xl"
                v-if="Object.keys(today).length === 0"
            >
                ぬるぽ
            </p>
            <template v-else>
                <div class="relative">
                    <div
                        class="relative box-border grid w-full grid-cols-1 items-stretch justify-around gap-x-2 gap-y-4 py-6 px-0 md:grid-cols-2 2xl:px-4"
                        ref="rssWrapper"
                    >
                        <div
                            v-for="(item, index) in today"
                            :key="index"
                            class="shrink-0 snap-start xl:pr-4"
                        >
                            <div v-if="item.nostream == true">
                                <div
                                    class="mx-auto flex w-fit flex-col items-end xl:flex-row xl:items-baseline"
                                >
                                    <p class="text-center text-sm xl:text-xl">
                                        配信予定がありません
                                    </p>
                                    <p class="px-3 text-xs xl:text-base">
                                        NO STREAM
                                    </p>
                                </div>
                                <p class="mt-1 text-center text-xl">
                                    {{ item.title }}
                                </p>
                            </div>
                            <StreamCard
                                v-else
                                :item="item"
                                class="h-full rounded-md shadow-sm shadow-custom-shadow"
                            ></StreamCard>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <div class="w-full select-none text-ptr-dark-brown">
            <h1 class="mt-3 flex items-center px-6 pt-0 pb-1 text-xl lg:px-12">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"
                    class="mr-2 h-5 w-5 fill-ptr-dark-brown"
                >
                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path
                        d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zM188.3 147.1c7.6-4.2 16.8-4.1 24.3 .5l144 88c7.1 4.4 11.5 12.1 11.5 20.5s-4.4 16.1-11.5 20.5l-144 88c-7.4 4.5-16.7 4.7-24.3 .5s-12.3-12.2-12.3-20.9V168c0-8.7 4.7-16.7 12.3-20.9z"
                    />
                </svg>
                Tomorrow's
            </h1>
        </div>
        <div class="py-3 px-2 xl:py-6 xl:px-4">
            <p
                class="text-center text-xl"
                v-if="Object.keys(tomorrow).length === 0"
            >
                ぬるぽ
            </p>
            <template v-else>
                <div class="relative">
                    <div
                        class="relative box-border grid w-full grid-cols-1 items-stretch justify-around gap-2 py-6 px-0 md:grid-cols-2 2xl:px-4"
                        ref="rssWrapper"
                    >
                        <div
                            v-for="(item, index) in tomorrow"
                            :key="index"
                            class="shrink-0 snap-start xl:pr-4"
                        >
                            <div v-if="item.nostream == true">
                                <div
                                    class="mx-auto flex w-fit flex-col items-end xl:flex-row xl:items-baseline"
                                >
                                    <p class="text-center text-sm xl:text-xl">
                                        配信予定がありません
                                    </p>
                                    <p class="px-3 text-xs xl:text-base">
                                        NO STREAM
                                    </p>
                                </div>
                                <p class="mt-1 text-center text-xl">
                                    {{ item.title }}
                                </p>
                            </div>
                            <StreamCard
                                v-else
                                :item="item"
                                class="h-full rounded-md shadow-sm shadow-custom-shadow"
                            ></StreamCard>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>
