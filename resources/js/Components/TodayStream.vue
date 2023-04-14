<script setup>
import { ref, onMounted, onUnmounted, computed, watch } from "vue";
import StreamCard from "./StreamCard.vue";
defineProps({
    today: Object,
    tomorrow: Object,
});
</script>
<template>
    <div
        class="relative rounded-t-lg rounded-br-lg rounded-bl-3xl bg-ptr-white px-4 pb-4 before:absolute before:top-2 before:right-2 before:-z-10 before:h-full before:w-full before:rounded-t-lg before:rounded-bl-3xl before:rounded-br-lg before:bg-yellow-500"
    >
        <div class="w-full select-none text-ptr-dark-brown">
            <h1
                class="flex items-center px-6 pt-12 pb-3 text-2xl lg:px-12 lg:pt-12">
                Steraming Schedules
            </h1>
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
            <div class="py-3 xl:py-6 px-2 xl:px-4">
                <p
                    class="text-center text-xl"
                    v-if="Object.keys(today).length === 0"
                >
                    ぬるぽ
                </p>
                <template v-else>
                    <div class="relative">
                        <div
                            class="relative box-border w-full grid grid-cols-1 md:grid-cols-2 items-stretch gap-2 py-6 px-0 2xl:px-4 justify-around"
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
                                        <p class="text-xs xl:text-base px-3">NO STREAM</p>
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
            <h1
                class="flex items-center px-6 pt-0 pb-1 text-xl lg:px-12 mt-3"
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
                Tomorrow's
            </h1>
        </div>
            <div class="py-3 xl:py-6 px-2 xl:px-4">
                <p
                    class="text-center text-xl"
                    v-if="Object.keys(tomorrow).length === 0"
                >
                    ぬるぽ
                </p>
                <template v-else>
                    <div class="relative">
                        <div
                            class="relative box-border grid grid-cols-1 md:grid-cols-2 w-full items-stretch gap-2 py-6 px-0 2xl:px-4 justify-around"
                            ref="rssWrapper"
                        >
                            <div
                                v-for="(item, index) in tomorrow"
                                :key="index"
                                class="shrink-0 snap-start  xl:pr-4"
                            >
                                <div v-if="item.nostream == true">
                                    <div
                                        class="mx-auto flex w-fit flex-col items-end xl:flex-row xl:items-baseline"
                                    >
                                        <p class="text-center text-sm xl:text-xl">
                                            配信予定がありません
                                        </p>
                                        <p class="text-xs xl:text-base px-3">NO STREAM</p>
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
