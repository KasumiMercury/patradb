<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted, computed, watch } from "vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import YouTubeEditwindow from "@/Components/YouTubeEditwindow.vue";
import axios from "axios";

defineProps({
    today: Object,
    month: Object,
    other: Object,
});

const stepConfig = [
    { step: 0, text: "Input URL" },
    { step: 1, text: "Create Data" },
    { step: 10, text: "Complete" },
];

const mounted = ref(false);
const videoError = ref(false);

const timerValue = ref(10);
let timer = null;

const step = ref(0);
const isCollabo = ref(true);
const canRegister = ref(true);
const inputUrl = ref("");
const videoId = ref("");

const videoInfos = ref(null);

const youtubeWindowRef = ref(null);
const youtubeComponent = ref();
const videoLength = ref(0);
const currentTime = ref(0);

// global methods
const encodeTime = (input) => {
    let hour = ("0" + Math.floor(input / 3600)).slice(-2);
    let min = ("0" + Math.floor((input % 3600) / 60)).slice(-2);
    let sec = ("0" + (input % 60)).slice(-2);
    return hour + ":" + min + ":" + sec;
};
const decodeTime = (input) => {
    let inputArray = input.split(":");
    return (
        Number(inputArray[0]) * 3600 +
        Number(inputArray[1]) * 60 +
        Number(inputArray[2])
    );
};

const setVideoLength = (length) => {
    videoLength.value = length;
};
const setCurrentTime = (time) => {
    currentTime.value = time;
    console.log(currentTime.value);
};

const seekDiff = (seek, diff) => {
    return seek(diff);
};

const play = (play) => {
    return play();
};
const pause = (pause) => {
    return pause();
};

// step 0
const getClipBoard = () => {
    if (navigator.clipboard) {
        navigator.clipboard.readText().then(function (text) {
            inputUrl.value = text;
        });
    } else {
        console.log('Clipboard not supported');
    }
};

const inputUrlError = ref(false);
// when inputUrl is changed, get videoId and check register
watch(inputUrl, () => {
    if (inputUrl.value == "") {
        inputUrlError.value = false;
        getVideoId();
    } else {
        const reg = new RegExp(
            /(?<!=\")\b(?:https?):\/\/(?:www\.)?(?:youtube\.com|youtu\.be)\/[\w!?/+\-|:=~;.,*&@#$%()'"[\]]+/g
        );
        if (reg.test(inputUrl.value)) {
            inputUrlError.value = false;
            getVideoId();
        } else {
            inputUrlError.value = true;
        }
    }
});

const getVideoId = () => {
    const ytUrl = inputUrl.value;
    const isShort = ytUrl.indexOf("watch");
    const isLive = ytUrl.indexOf("/live/");
    const WhereTime = ytUrl.indexOf("?t=");
    const WhereFeauture = ytUrl.indexOf("?feature");
    const WhereDomain = ytUrl.indexOf("youtu.be/") + 9;
    const WhereS = ytUrl.indexOf("=") + 1;
    const whereQuery = ytUrl.indexOf("&");
    if (isShort != -1) {
        if (WhereTime != -1) {
            videoId.value = ytUrl.slice(WhereS, WhereTime);
        } else if (whereQuery != -1) {
            videoId.value = ytUrl.slice(WhereS, whereQuery);
        } else {
            videoId.value = ytUrl.slice(WhereS);
        }
    } else if (isLive != -1) {
        if (WhereFeauture != -1) {
            videoId.value = ytUrl.slice(WhereLive, WhereFeauture);
        } else {
            videoId.value = ytUrl.slice(WhereLive);
        }
    } else {
        if (WhereTime != -1) {
            videoId.value = ytUrl.slice(WhereDomain, WhereTime);
        } else {
            videoId.value = ytUrl.slice(WhereDomain);
        }
    }
    checkRegister();
};
const checkRegister = () => {
    axios
        .get(route("check.collabo", { videoId: videoId.value }))
        .then((res) => {
            canRegister.value = res.data.canRegister;
        });
};
const checkVideoId = () => {
    if (videoId.value == "") {
        // URLが入力されていない場合
        videoError.value = true;
        inputUrl.value = "";
        videoId.value = "";
    } else {
        axios
            .get(route("check.video.exist", { videoId: videoId.value }))
            .then((res) => {
                if (res.data.isError) {
                    // URLが正しくない場合
                    videoError.value = true;
                    inputUrl.value = "";
                    videoId.value = "";
                    return;
                }
                if (res.data.isCollabo) {
                    if (res.data.isExist) {
                        // 既にコラボが存在する場合
                        // ジャンプページに遷移する
                        step.value = 10;
                        JumpTimer();
                    } else {
                        // コラボが存在しない場合
                        // コラボデータ作成ページに遷移する
                        // videoInfosをコピーする
                        isCollabo.value = true;
                        videoInfos.value = res.data.videoInfos;
                        console.log(videoInfos.value);
                        step.value = 1;
                    }
                } else {
                    // パトラの動画の場合
                    // メモリ登録ページに遷移する
                    isCollabo.value = false;
                    step.value = 1;
                }
            });
    }
};
const JumpTimer = () => {
    timerValue.value = 10;
    timer = setInterval(() => {
        timerValue.value--;
        if (timerValue.value == 0) {
            clearInterval(timer);
            router.visit(route("toppage"));
        }
    }, 1000);
};
const resetForm = () => {
    clearInterval(timer);
    inputUrl.value = "";
    videoId.value = "";
    videoError.value = false;
    step.value = 0;
};

onMounted(() => {
    mounted.value = true;
    clearInterval(timer);
});
</script>
<script>
import AppLayout from "../Layouts/AppLayout.vue";
export default {
    layout: AppLayout,
};
</script>
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
    <div>
        <Head>
            <title>Launch</title>
        </Head>
        <Teleport to='[data-slot="header"]' v-if="mounted">
            <p class="text-xs font-semibold text-gray-800">Launch</p>
        </Teleport>

        <div>
            <div v-if="$page.props.auth.user" class="mx-auto my-3 w-fit">
                <ul class="steps">
                    <li
                        v-for="(stepItem, index) in stepConfig"
                        :key="index"
                        class="step"
                        :class="
                            stepItem.step <= step
                                ? 'step-primary'
                                : 'step-secondary'
                        "
                    >
                        <p
                            :class="
                                stepItem.step <= step
                                    ? 'text-ptr-main'
                                    : 'text-ptr-dark-brown'
                            "
                        >
                            {{ stepItem.text }}
                        </p>
                    </li>
                </ul>
            </div>

            <div
                v-if="step == 0"
                class="mt-12 flex-col justify-center gap-12 px-1 lg:px-8"
            >
                <div
                    v-if="!$page.props.auth.user"
                    class="mx-auto max-w-5xl text-center"
                >
                    <p class="font-bold text-ptr-main text-sm md:text-base">
                        この機能は現在、ログインユーザーのみ使用可能です。
                    </p>
                    <p class="my-3 text-xs md:text-sm">
                        だまして悪いが、仕様なんでな　<Link
                            class="px-1n link-primary link"
                            :href="route('transition.login')"
                            >ログイン</Link
                        >してもらおう
                    </p>
                </div>
                <div class="relative mx-auto mt-6 max-w-7xl">
                    <!-- <transition>
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
                    </transition> -->
                    <div class="px-6 py-4">
                        <div class="mx-auto mb-2 aspect-video max-h-32">
                            <img
                                v-if="videoId"
                                :src="
                                    'https://img.youtube.com/vi/' +
                                    videoId +
                                    '/maxresdefault.jpg'
                                "
                                alt="inputed url thumbnail"
                            />
                        </div>
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
                        <div
                            v-if="inputUrl != ''"
                            class="text-right text-xs font-bold md:text-base"
                        >
                            <p v-if="inputUrlError" class="text-red-600">
                                YouTubeのURLを入力してください
                            </p>
                            <p v-if="videoError" class="text-red-600">
                                動画を検出できませんでした。URLを確認してください。
                            </p>
                            <p v-if="!canRegister" class="text-red-600">
                                この動画は既にコラボ動画として登録されています。
                            </p>
                            <p
                                v-if="canRegister && !inputUrlError"
                                class="text-right text-ptr-dark-brown"
                            >
                                サムネイルが正しいことを確認してください。
                            </p>
                        </div>
                        <transition>
                            <div
                                class="mx-auto mt-4 w-fit md:mt-12"
                                v-if="
                                    inputUrl != '' &&
                                    !inputUrlError &&
                                    canRegister
                                "
                            >
                                <button
                                    class="flex items-center rounded-xl bg-[#c20063] py-3 px-12 text-3xl text-white shadow-sm shadow-[#550341]"
                                    @click="checkVideoId()"
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
                    <div v-if="!$page.props.auth.user" class="absolute top-0 left-0 w-full h-full bg-ptr-dark-brown/70 rounded-md">
                    </div>
                </div>
                    <div class="mx-auto w-fit mt-2">
                        <Link
                            v-if="canRegister"
                            class="btn"
                            :href="route('memory.videos')"
                            >パトラチャンネルの動画検索はこちら</Link
                        >
                    </div>
            </div>

            <!-- Create Collabo Data -->
            <div v-if="isCollabo">
                <div v-if="step == 1">
                    <div
                        ref="youtubeWindowRef"
                        class="relative mx-auto w-full max-w-5xl px-6"
                    >
                        <div class="aspect-video w-full">
                            <YouTubeEditwindow
                                ref="youtubeComponent"
                                :videoId="videoId"
                                :isFloatWindow="false"
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
                                            v-on:click="seekDiff(seek, -600)"
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
                                            v-on:click="seekDiff(seek, -10)"
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
                                            v-on:click="seekDiff(seek, -1)"
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
                                            v-on:click="seekDiff(seek, 1)"
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
                                            v-on:click="seekDiff(seek, 10)"
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
                                            v-on:click="seekDiff(seek, 600)"
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
                            </YouTubeEditwindow>
                        </div>
                    </div>
                    <p>step 1 collabo data</p>
                </div>
            </div>

            <!-- Register Memory -->
            <div v-else>
                <div v-if="step == 1">
                    <p>step 1 memory</p>
                </div>
            </div>

            <!-- Jump Page -->
            <div
                v-if="step == 10"
                class="mx-auto mt-12 max-w-7xl px-6 pt-12 text-center text-xl"
            >
                <p class="text-ptr-dark-pink">
                    指定の動画は既に登録されています。{{
                        timerValue
                    }}秒後にScheduleページに遷移します。
                </p>
                <div class="mx-auto mt-24 flex w-fit flex-col">
                    <Link
                        as="button"
                        :href="route('toppage')"
                        class="rounded-md bg-ptr-dark-brown px-6 py-3 text-ptr-white"
                        >Scheduleページ</Link
                    >
                    <button
                        @click="resetForm()"
                        class="my-6 rounded-md bg-ptr-dark-brown px-6 py-3 text-ptr-white"
                    >
                        別の動画を登録する
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
