<script setup>
import { Head,Link } from "@inertiajs/vue3";
import { ref, computed, onMounted, inject, onUnmounted } from "vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import ResponsiveYoutubeWindow from "@/Components/ResponsiveYoutubeWindow.vue";
import InteractiveTimeline from "@/Components/InteractiveTimeline.vue";

const $cookies = inject("$cookies");

const step = ref(1);
const inputUrl = ref("");
const videoId = ref("0kNH45w23aA");
const videoLength = ref(3600);
const currentTime = ref(20);

const isFloatWindow = ref(false);
const youtubeWindowRef = ref(null);
const youtubeComponent = ref();

onMounted(() => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                isFloatWindow.value = false;
            } else {
                isFloatWindow.value = true;
            }
        });
    });
    observer.observe(youtubeWindowRef.value);
    mounted.value = true
});

const existCookies = () => {
    let cookiesIsExist = $cookies.isKey("createrPlayerForm");
    return cookiesIsExist;
};
const loadCookies = () => {
    let temp = $cookies.get("createrPlayerForm");
    videoId.value = temp["videoId"];
    step.value = temp["step"];
};

const nextStep = () => {
    step.value++;
};
const prevStep = () => {
    step.value--;
};

const getClipBoard = () => {
    if (navigator.clipboard) {
        navigator.clipboard.readText().then(function (text) {
            inputUrl.value = text;
        });
    }
};

const getVideoId = () => {
    const ytUrl = inputUrl.value;
    let isShort = ytUrl.indexOf("watch");
    if (isShort != -1) {
        let WhereS = ytUrl.indexOf("=") + 1;
        let WhereTime = ytUrl.indexOf("&t");
        if (WhereTime != -1) {
            videoId.value = ytUrl.slice(WhereS, WhereTime);
        } else {
            let whereQuery = ytUrl.indexOf("&");
            if (whereQuery != -1) {
                videoId.value = ytUrl.slice(WhereS, whereQuery);
            } else {
                videoId.value = ytUrl.slice(WhereS);
            }
        }
    } else {
        let isLive = ytUrl.indexOf("/live/");
        if (isLive != -1) {
            let WhereLive = ytUrl.indexOf("/live/") + 6;
            let WhereFeauture = ytUrl.indexOf("?feature");
            if (WhereFeauture != -1) {
                videoId.value = ytUrl.slice(WhereLive, WhereFeauture);
            } else {
                videoId.value = ytUrl.slice(WhereLive);
            }
        } else {
            let WhereDomain = ytUrl.indexOf("youtu.be/") + 9;
            let WhereTime = ytUrl.indexOf("?t=");
            if (WhereTime != -1) {
                videoId.value = ytUrl.slice(WhereDomain, WhereTime);
            } else {
                videoId.value = ytUrl.slice(WhereDomain);
            }
        }
    }
    if (videoId.value != "") {
        nextStep();
    }
    $cookies.set("createrPlayerForm", { step: 1, videoId: videoId.value });
};

const inputUrlError = computed(() => {
    if (inputUrl.value == "") {
        return false;
    } else {
        const reg = new RegExp(
            /(?<!=\")\b(?:https?):\/\/(?:www\.)?(?:youtube\.com|youtu\.be)\/[\w!?/+\-|:=~;.,*&@#$%()'"[\]]+/g
        );
        return !reg.test(inputUrl.value);
    }
});

const convertTime = (input) => {
    let hour = Math.floor(input / 3600);
    let min = Math.floor((input % 3600) / 60);
    let sec = input % 60;
    return hour + ":" + min + ":" + sec;
};

const setVideoLength = (length) => {
    videoLength.value = length;
};

const setCurrentTime = (time) => {
    //console.log(time);
    currentTime.value = time;
};

const seekDif = (seek, diff) => {
    return seek(diff);
};

const play = (play) => {
    return play();
};
const pause = (pause) => {
    return pause();
};

const timelinePlayAt = (time) => {
    let youtube = youtubeComponent.value;
    youtube.playAt(time);
    currentTime.value = time;
};

const mounted = ref(false);

</script>

<style scoped>
.v-enter-active,
.v-leave-active {
    transition: opacity 1s ease, transform 1s ease;
    transform: translateX(0);
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
    transform: translateX(-1rem);
}
</style>
<style>
.videoWindow {
    width: 100% !important;
    height: 100% !important;
    margin: 0;
}
.videoWindow iframe {
    position: absolute !important;
    width: 100% !important;
    height: 100% !important;
    margin: 0;
}
</style>
<script>
import AppLayout from "../Layouts/AppLayout.vue";

export default {
    layout: AppLayout,
};
</script>
<template>
    <Head>
        <title>CreatePlayer</title>
    </Head>
    <Teleport to='[data-slot="header"]' v-if="mounted">

        <p class="text-xs font-semibold text-gray-800"><Link :href="route('adddata')" class="underline">AddData</Link> / CreatePlayer</p>
    </Teleport>
    <div>
        <div class="w-full">
            <div class="mx-auto w-full pt-4">
                <div
                    v-if="step != 0"
                    class="mx-auto mb-3 flex max-w-7xl items-start px-8 lg:mb-6"
                >
                    <button
                        class="flex items-center rounded-lg bg-gray-800 py-1 px-3 text-base text-white lg:py-2 lg:px-6 lg:text-lg"
                        @click="prevStep()"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 256 512"
                            class="mr-1 h-3 w-3 fill-white lg:h-4 lg:w-4"
                        >
                            <!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                            <path
                                d="M9.4 278.6c-12.5-12.5-12.5-32.8 0-45.3l128-128c9.2-9.2 22.9-11.9 34.9-6.9s19.8 16.6 19.8 29.6l0 256c0 12.9-7.8 24.6-19.8 29.6s-25.7 2.2-34.9-6.9l-128-128z"
                            />
                        </svg>
                        Back
                    </button>
                </div>
                <div
                    class="relative mx-auto w-full max-w-5xl px-6"
                    ref="youtubeWindowRef"
                >
                    <div class="aspect-video w-full" v-if="step >= 1">
                        <ResponsiveYoutubeWindow
                            ref="youtubeComponent"
                            :videoId="videoId"
                            :isFloatWindow="isFloatWindow"
                            @currentTimeUpdate="setCurrentTime"
                            @getVideoLength="setVideoLength"
                        >
                            <template
                                v-slot:activator="{
                                    play,
                                    pause,
                                    seek,
                                    ytStatus,
                                }"
                            >
                                <div class="flex justify-around">
                                    <button
                                        v-on:click="seekDif(seek, -600)"
                                        class="text-sm text-gray-900 lg:text-base"
                                    >
                                        <svg
                                            stroke-width="1"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                            aria-hidden="true"
                                            class="h-8 w-8 fill-none stroke-white lg:h-12 lg:w-12"
                                        >
                                            <path
                                                transform="translate(-5)"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5"
                                            ></path>
                                            <text
                                                x="10"
                                                y="14"
                                                font-size="5.5"
                                                stroke-width="0.1"
                                                fill="#fff"
                                            >
                                                -10m
                                            </text>
                                        </svg>
                                    </button>
                                    <button
                                        v-on:click="seekDif(seek, -10)"
                                        class="text-sm text-gray-900 lg:text-base"
                                    >
                                        <svg
                                            stroke-width="1"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                            aria-hidden="true"
                                            class="h-8 w-8 fill-none stroke-white lg:h-12 lg:w-12"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15.75 19.5L8.25 12l7.5-7.5"
                                            ></path>
                                            <text
                                                x="12"
                                                y="14"
                                                font-size="5.5"
                                                stroke-width="0.1"
                                                fill="#fff"
                                            >
                                                -10s
                                            </text>
                                        </svg>
                                    </button>
                                    <button
                                        v-on:click="seekDif(seek, -1)"
                                        class="text-sm text-gray-900 lg:text-base"
                                    >
                                        <svg
                                            stroke-width="1"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                            aria-hidden="true"
                                            class="h-8 w-8 fill-none stroke-white lg:h-12 lg:w-12"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                transform="rotate(180,12,12)"
                                                d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z"
                                            ></path>
                                            <text
                                                x="9"
                                                y="14"
                                                font-size="5.5"
                                                stroke-width="0.1"
                                                fill="#fff"
                                            >
                                                -1s
                                            </text>
                                        </svg>
                                    </button>
                                    <button
                                        v-if="ytStatus"
                                        v-on:click="pause(pause)"
                                        class="text-sm text-gray-900 lg:text-base"
                                    >
                                        <svg
                                            class="h-6 w-6 fill-white lg:h-10 lg:w-10"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 320 512"
                                        >
                                            <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                            <path
                                                d="M48 64C21.5 64 0 85.5 0 112V400c0 26.5 21.5 48 48 48H80c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48H48zm192 0c-26.5 0-48 21.5-48 48V400c0 26.5 21.5 48 48 48h32c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48H240z"
                                            />
                                        </svg>
                                    </button>
                                    <button
                                        v-else
                                        v-on:click="play(play)"
                                        class="text-sm text-gray-900 lg:text-base"
                                    >
                                        <svg
                                            class="h-6 w-6 fill-white lg:h-10 lg:w-10"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 384 512"
                                        >
                                            <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                            <path
                                                d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z"
                                            />
                                        </svg>
                                    </button>
                                    <button
                                        v-on:click="seekDif(seek, 1)"
                                        class="rounded-xl text-sm text-gray-900 lg:text-base"
                                    >
                                        <svg
                                            stroke-width="1"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                            aria-hidden="true"
                                            class="h-8 w-8 fill-none stroke-white lg:h-12 lg:w-12"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z"
                                            ></path>
                                            <text
                                                x="6"
                                                y="14"
                                                font-size="5.5"
                                                stroke-width="0.1"
                                                fill="#fff"
                                            >
                                                +1s
                                            </text>
                                        </svg>
                                    </button>
                                    <button
                                        v-on:click="seekDif(seek, 10)"
                                        class="text-sm text-gray-900 lg:text-base"
                                    >
                                        <svg
                                            stroke-width="1"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                            aria-hidden="true"
                                            class="h-8 w-8 fill-none stroke-white lg:h-12 lg:w-12"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M8.25 4.5l7.5 7.5-7.5 7.5"
                                            ></path>
                                            <text
                                                x="-1"
                                                y="14"
                                                font-size="5.5"
                                                stroke-width="0.1"
                                                fill="#fff"
                                            >
                                                +10s
                                            </text>
                                        </svg>
                                    </button>
                                    <button
                                        v-on:click="seekDif(seek, 600)"
                                        class="text-sm text-gray-900 lg:text-base"
                                    >
                                        <svg
                                            stroke-width="1"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                            aria-hidden="true"
                                            class="h-8 w-8 fill-none stroke-white lg:h-12 lg:w-12"
                                        >
                                            <path
                                                transform="translate(5)"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M11.25 4.5l7.5 7.5-7.5 7.5m-6-15l7.5 7.5-7.5 7.5"
                                            ></path>
                                            <text
                                                x="0"
                                                y="14"
                                                font-size="5.5"
                                                stroke-width="0.1"
                                                fill="#fff"
                                            >
                                                +10m
                                            </text>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </ResponsiveYoutubeWindow>
                    </div>
                </div>
                <div v-if="step == 0" class="mx-auto mt-12 max-w-7xl px-6">
                    <transition>
                        <div
                            class="mx-auto my-4 flex w-fit flex-col items-center"
                            v-if="existCookies()"
                        >
                            <p class="animate-bounce text-sm text-[#c20063]">
                                前回、入力途中のデータがあります。
                            </p>
                            <button
                                class="my-2 rounded-lg bg-[#c20063] py-2 px-8 text-base text-white"
                                @click="loadCookies()"
                            >
                                Load
                            </button>
                        </div>
                    </transition>
                    <InputLabel for="inputUrl" value="YouTube Video URL" />
                    <div class="flex w-full flex-col items-end">
                        <TextInput
                            id="inputUrl"
                            type="text"
                            class="block w-full"
                            v-model="inputUrl"
                            required
                            autofocus
                        />
                        <button
                            class="my-2 rounded-xl bg-gray-700 px-6 py-2 text-xl text-white"
                            @click="getClipBoard()"
                        >
                            Paste
                        </button>
                    </div>
                    <p v-if="inputUrlError" class="text-right text-red-600">
                        YouTubeのURLを入力してください
                    </p>
                    <transition>
                        <div
                            class="mx-auto mt-12 w-fit"
                            v-if="inputUrl != '' && !inputUrlError"
                        >
                            <button
                                class="flex items-center rounded-xl bg-[#c20063] py-3 px-12 text-3xl text-white shadow-sm shadow-[#550341]"
                                @click="getVideoId()"
                            >
                                Next
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 256 512"
                                    class="h-8 w-8 animate-side-bounce fill-white"
                                >
                                    <!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <path
                                        d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"
                                    />
                                </svg>
                            </button>
                        </div>
                    </transition>
                </div>
                <div v-if="step == 1" class="mb-12 mt-0 min-h-screen w-full">
                    <InteractiveTimeline
                        :videoLength="videoLength"
                        :currentTime="currentTime"
                        @playAt="timelinePlayAt"
                    ></InteractiveTimeline>
                </div>
                <div
                    v-if="step != 2"
                    class="mx-auto mt-3 mb-96 flex max-w-7xl justify-center px-8 pb-24 lg:mt-6"
                >
                    <button
                        class="flex items-center rounded-lg bg-ptr-dark-pink py-1 px-3 text-xl text-white shadow-sm shadow-[#550341] lg:py-2 lg:px-6 lg:text-3xl"
                    >
                        Next
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 256 512"
                            class="h-4 w-4 animate-side-bounce fill-white pl-1 lg:h-6 lg:w-6"
                        >
                            <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                            <path
                                d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
