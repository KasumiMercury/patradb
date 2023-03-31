<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, computed, onMounted, inject, onUnmounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import ResponsiveYoutubeWindow from "@/Components/ResponsiveYoutubeWindow.vue";
import InteractiveTimeline from "@/Components/InteractiveTimeline.vue";
import YouTube from "vue3-youtube";

const TopNavStyle = ref({
    "background-color": "#2d2a2d",
    "border-bottom": "5px solid",
    "border-image-source":
        "linear-gradient(45deg, #B67B03 0%, #DAAF08 45%, #FEE9A0 70%, #DAAF08 85%, #B67B03 90% 100%)",
    "border-image-slice": 1,
});
const TopNavFontStyle = ref({
    color: "#fffafb",
});

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

const timelinePlayAt = (time) => {
    let youtube = youtubeComponent.value;
    youtube.playAt(time);
    currentTime.value = time;
};
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
<template>
    <AppLayout
        title="AddData"
        :TopNavStyle="TopNavStyle"
        :TopNavFontStyle="TopNavFontStyle"
    >
        <template #header>
            <p class="font-semibold text-xs text-gray-800">
                <Link :href="route('adddata')" class="underline">AddData</Link>
                / Player
            </p>
        </template>

        <div class="w-full">
            <div class="mx-auto pt-4 w-full">
                <div
                    v-if="step != 0"
                    class="ml-0 lg:ml-2 w-fit mb-3 lg:mb-6 px-8"
                >
                    <button
                        class="text-base lg:text-lg py-1 lg:py-2 px-3 lg:px-6 bg-gray-800 text-white rounded-lg flex items-center"
                        @click="prevStep()"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 256 512"
                            class="w-4 h-4 lg:w-5 lg:h-5 fill-white"
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
                    class="mx-auto max-w-5xl w-full aspect-video relative px-6"
                    ref="youtubeWindowRef"
                >
                    <template v-if="step != 0">
                        <ResponsiveYoutubeWindow
                            ref="youtubeComponent"
                            :videoId="videoId"
                            :isFloatWindow="isFloatWindow"
                            @currentTimeUpdate="setCurrentTime"
                            @getVideoLength="setVideoLength"
                        >
                            <template v-slot:activator="{ play, seek }">
                                <div class="flex justify-around">
                                    <button
                                        v-on:click="seekDif(seek, -600)"
                                        class="text-sm lg:text-base text-gray-900"
                                    >
                                        <svg
                                            stroke-width="1"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                            aria-hidden="true"
                                            class="w-8 h-8 lg:w-12 lg:h-12 stroke-white fill-none"
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
                                        class="text-sm lg:text-base text-gray-900"
                                    >
                                        <svg
                                            stroke-width="1"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                            aria-hidden="true"
                                            class="w-8 h-8 lg:w-12 lg:h-12 stroke-white fill-none"
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
                                        class="text-sm lg:text-base text-gray-900"
                                    >
                                        <svg
                                            stroke-width="1"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                            aria-hidden="true"
                                            class="w-8 h-8 lg:w-12 lg:h-12 stroke-white fill-none"
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
                                        v-on:click="seekDif(seek, 1)"
                                        class="text-sm lg:text-base rounded-xl text-gray-900"
                                    >
                                        <svg
                                            stroke-width="1"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                            aria-hidden="true"
                                            class="w-8 h-8 lg:w-12 lg:h-12 stroke-white fill-none"
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
                                        class="text-sm lg:text-base text-gray-900"
                                    >
                                        <svg
                                            stroke-width="1"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                            aria-hidden="true"
                                            class="w-8 h-8 lg:w-12 lg:h-12 stroke-white fill-none"
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
                                        class="text-sm lg:text-base text-gray-900"
                                    >
                                        <svg
                                            stroke-width="1"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                            aria-hidden="true"
                                            class="w-8 h-8 lg:w-12 lg:h-12 stroke-white fill-none"
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
                    </template>
                </div>
                <div v-if="step == 0" class="mt-12 px-6">
                    <transition>
                        <div
                            class="mx-auto w-fit my-4 flex flex-col items-center"
                            v-if="existCookies()"
                        >
                            <p class="text-sm text-[#c20063] animate-bounce">
                                前回、入力途中のデータがあります。
                            </p>
                            <button
                                class="text-base my-2 py-2 px-8 bg-[#c20063] text-white rounded-lg"
                                @click="loadCookies()"
                            >
                                Load
                            </button>
                        </div>
                    </transition>
                    <InputLabel for="inputUrl" value="YouTube Video URL" />
                    <div class="w-full flex flex-col items-end">
                        <TextInput
                            id="inputUrl"
                            type="text"
                            class="block w-full"
                            v-model="inputUrl"
                            required
                            autofocus
                        />
                        <button
                            class="text-xl rounded-xl bg-gray-700 text-white px-6 py-2 my-2"
                            @click="getClipBoard"
                        >
                            Paste
                        </button>
                    </div>
                    <p v-if="inputUrlError" class="text-red-600 text-right">
                        YouTubeのURLを入力してください
                    </p>
                    <transition>
                        <div
                            class="mx-auto w-fit mt-12"
                            v-if="inputUrl != '' && !inputUrlError"
                        >
                            <button
                                class="text-3xl py-3 px-12 bg-[#c20063] text-white rounded-xl flex items-center shadow-sm shadow-[#550341]"
                                @click="getVideoId()"
                            >
                                Next
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 256 512"
                                    class="w-8 h-8 fill-white animate-side-bounce"
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
                <div v-if="step == 1" class="min-h-screen w-full mb-12 mt-0">
                    <InteractiveTimeline
                        :videoLength="videoLength"
                        :currentTime="currentTime"
                        @playAt="timelinePlayAt"
                    ></InteractiveTimeline>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
