<script setup>
import { ref, onMounted, computed, onUnmounted } from "vue";
import { Head, Link, useForm, usePage, router } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import YouTubeEditwindow from "@/Components/YouTubeEditwindow.vue";
import axios from "axios";

const mounted = ref(false);
const step = ref(0);
const inputUrl = ref("");
const videoId = ref("0kNH45w23aA");
const youtubeWindowRef = ref(null);
const youtubeComponent = ref();
const videoLength = ref(0);
const currentTime = ref(0);

const timeArray = ref([]);
const showConfirm = ref(false);
const showRegisterRange = ref(false);
let routeTopTimer = null;
let timer = null;
const timerValue = ref(10);

const form = useForm({
    videoId: "",
    start: null,
    end: 10,
    handleName: "",
    showName: true,
});

if (usePage().props.auth.user !== null) {
    form.handleName = usePage().props.auth.user.name;
    if (usePage().props.auth.user.role == "admin") {
        form.showName = false;
    }
}

const playAt = (time) => {
    let youtube = youtubeComponent.value;
    youtube.playAt(time);
    currentTime.value = time;
};

onMounted(() => (mounted.value = true));

const existCookies = () => {
    let cookiesIsExist = $cookies.isKey("collaboParent");
    return cookiesIsExist;
};
const loadCookies = () => {
    let temp = $cookies.get("collaboParent");
    videoId.value = temp["videoId"];
    step.value = 1;
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
    checkExist();
};

const getClipBoard = () => {
    if (navigator.clipboard) {
        navigator.clipboard.readText().then(function (text) {
            inputUrl.value = text;
        });
    }
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

const timeRegisterStep = () => {
    step.value = 1;
    $cookies.set("playerParent", { videoId: videoId.value });
};

const checkExist = () => {
    console.log(videoId.value);
    if (videoId.value != "") {
        form.videoId = videoId.value;
        axios
            .get(route("check.collabo", { videoId: videoId.value }))
            .then((res) => {
                console.log(res);
                if (res.data.isExist) {
                    console.log("exist");
                    step.value = 2;
                    timerValue.value = 10;
                    routeTopTimer = setTimeout(() => {
                        router.visit(route("toppage"));
                    }, 10000);
                    timer = setInterval(() => {
                        timerValue.value--;
                    }, 1000);
                } else {
                    timeRegisterStep();
                }
            });
    }
};

const setVideoLength = (length) => {
    videoLength.value = length;
};

const setCurrentTime = (time) => {
    currentTime.value = time;
};

const registerWhole = () => {
    form.start = null;
    form.end = null;
    showRegisterRange.value = false;
    showConfirm.value = true;
};

const registerPart = () => {
    form.end = 10;
    showConfirm.value = false;
    showRegisterRange.value = true;
};

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
const registerVideo = () => {
    console.log(form);
    form.post(route("post.collabo"), {
        onFinish: () => {
            $cookies.remove("collaboParent");
        },
    });
};
const prevStep = () => {
    step.value--;
};
onUnmounted(() => {
    console.log("clear");
    clearTimeout(routeTopTimer);
    clearInterval(timer);
});

const otherRegister = () => {
    step.value = 0;
    clearTimeout(routeTopTimer);
    clearInterval(timer);
};
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
    <Head>
        <title>RegisterCollabo</title>
    </Head>
    <Teleport to='[data-slot="header"]' v-if="mounted">
        <p class="text-xs font-semibold text-gray-800">
            <Link :href="route('adddata')" class="underline">AddData</Link> /
            RegisterCollabo
        </p>
    </Teleport>
    <div>
        <div class="w-full">
            <div class="mx-auto w-full pt-4">
                <div
                    v-if="step != 0 && step != 2"
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
                    <div class="aspect-video w-full" v-if="step == 1">
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
                        </YouTubeEditwindow>
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
                <div v-if="step == 1" class="pt-6">
                    <div v-if="showConfirm">
                        <div class="mx-auto mt-20 max-w-xl">
                            <div v-if="!$page.props.auth.user">
                                <InputLabel
                                    for="handleName"
                                    value="Handle Name"
                                />
                                <TextInput
                                    id="handleName"
                                    v-model="form.handleName"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                />
                            </div>

                            <div class="mx-auto mt-6 w-fit">
                                <label class="flex items-center">
                                    <Checkbox
                                        v-model:checked="form.showName"
                                        name="showName"
                                    />
                                    <span class="ml-2 text-sm text-gray-600"
                                        >あなたのハンドルネームを登録者として表示しますか？</span
                                    >
                                </label>
                            </div>
                        </div>
                        <div
                            class="mx-auto mt-12 flex w-fit flex-col justify-center"
                        >
                            <p
                                v-if="form.handleName == ''"
                                class="animate-bounce text-center text-red-600"
                            >
                                ハンドルネームを入力してください。（データ操作時に必要です。）
                            </p>
                            <button
                                class="mx-auto flex w-fit items-center rounded-xl bg-[#c20063] py-3 px-12 text-3xl text-white shadow-sm shadow-[#550341]"
                                @click="registerVideo"
                            >
                                Launch
                            </button>
                        </div>
                    </div>
                    <div
                        v-if="!showConfirm"
                        class="mx-auto mt-2 flex w-fit flex-col"
                    >
                        <button
                            class="flex flex-row items-center rounded-md bg-ptr-main px-8 py-4 text-2xl text-white"
                            @click="registerWhole"
                        >
                            この動画全体を登録<svg
                                class="mx-2 h-8 w-8 fill-white p-1"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 512 512"
                            >
                                <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                <path
                                    d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 192 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l210.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128zM160 96c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 32C43 32 0 75 0 128L0 384c0 53 43 96 96 96l64 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l64 0z"
                                />
                            </svg>
                        </button>
                    </div>
                    <div
                        v-if="showRegisterRange"
                        class="mx-auto mt-12 max-w-xl p-3"
                    >
                        <div class="flex flex-col gap-4">
                            <div class="flex flex-row items-center">
                                <button
                                    class="mr-1 rounded bg-white shadow-sm shadow-custom-shadow"
                                    @click="form.start = currentTime"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 448 512"
                                        class="h-8 w-8 p-1"
                                    >
                                        <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path
                                            d="M176 0c-17.7 0-32 14.3-32 32s14.3 32 32 32h16V98.4C92.3 113.8 16 200 16 304c0 114.9 93.1 208 208 208s208-93.1 208-208c0-41.8-12.3-80.7-33.5-113.2l24.1-24.1c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L355.7 143c-28.1-23-62.2-38.8-99.7-44.6V64h16c17.7 0 32-14.3 32-32s-14.3-32-32-32H224 176zm72 192V320c0 13.3-10.7 24-24 24s-24-10.7-24-24V192c0-13.3 10.7-24 24-24s24 10.7 24 24z"
                                        />
                                    </svg>
                                </button>
                                <input
                                    ref="input"
                                    class="grow rounded-md border-gray-300 px-2 py-1 shadow-sm shadow-custom-shadow focus:border-ptr-main focus:ring-ptr-main"
                                    :value="encodeTime(form.start)"
                                    @input="
                                        form.start = decodeTime(
                                            $event.target.value
                                        )
                                    "
                                    required
                                />
                                <button
                                    class="ml-1 rounded bg-white shadow-sm shadow-custom-shadow"
                                    @click="playAt(form.start)"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 384 512"
                                        class="h-8 w-8 p-1"
                                    >
                                        <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path
                                            d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z"
                                        />
                                    </svg>
                                </button>
                            </div>
                            <div class="flex flex-row items-center">
                                <button
                                    class="mr-1 rounded bg-white shadow-sm shadow-custom-shadow"
                                    @click="form.end = currentTime"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 448 512"
                                        class="h-8 w-8 p-1"
                                    >
                                        <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path
                                            d="M176 0c-17.7 0-32 14.3-32 32s14.3 32 32 32h16V98.4C92.3 113.8 16 200 16 304c0 114.9 93.1 208 208 208s208-93.1 208-208c0-41.8-12.3-80.7-33.5-113.2l24.1-24.1c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L355.7 143c-28.1-23-62.2-38.8-99.7-44.6V64h16c17.7 0 32-14.3 32-32s-14.3-32-32-32H224 176zm72 192V320c0 13.3-10.7 24-24 24s-24-10.7-24-24V192c0-13.3 10.7-24 24-24s24 10.7 24 24z"
                                        />
                                    </svg>
                                </button>
                                <input
                                    ref="input"
                                    class="grow rounded-md border-gray-300 px-2 py-1 shadow-sm shadow-custom-shadow focus:border-ptr-main focus:ring-ptr-main"
                                    :value="encodeTime(form.end)"
                                    @input="
                                        form.end = decodeTime($event.target.end)
                                    "
                                />
                                <button
                                    class="ml-1 rounded bg-white shadow-sm shadow-custom-shadow"
                                    @click="playAt(form.start)"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 384 512"
                                        class="h-8 w-8 p-1"
                                    >
                                        <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path
                                            d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z"
                                        />
                                    </svg>
                                </button>
                            </div>
                            <div class="mr-0 ml-auto w-fit">
                                <button
                                    class="flex flex-row items-center rounded-md bg-ptr-dark-brown px-3 py-2 text-ptr-white"
                                    @click="form.end = videoLength"
                                >
                                    動画の最後を設定する
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 320 512"
                                        class="h-6 w-6 fill-ptr-white p-1"
                                    >
                                        <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path
                                            d="M52.5 440.6c-9.5 7.9-22.8 9.7-34.1 4.4S0 428.4 0 416V96C0 83.6 7.2 72.3 18.4 67s24.5-3.6 34.1 4.4l192 160L256 241V96c0-17.7 14.3-32 32-32s32 14.3 32 32V416c0 17.7-14.3 32-32 32s-32-14.3-32-32V271l-11.5 9.6-192 160z"
                                        />
                                    </svg>
                                </button>
                            </div>
                            <p
                                v-if="form.start >= form.end"
                                class="mt-6 text-center leading-6 text-red-600"
                            >
                                終了時間が開始時間よりも前になっています。<br />終了時刻は自動的に動画の最後に設定されます。
                            </p>
                        </div>
                        <div class="mx-auto mt-20 max-w-xl">
                            <div v-if="!$page.props.auth.user">
                                <InputLabel
                                    for="handleName"
                                    value="Handle Name"
                                />
                                <TextInput
                                    id="handleName"
                                    v-model="form.handleName"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                />
                            </div>

                            <div class="mx-auto mt-6 w-fit">
                                <label class="flex items-center">
                                    <Checkbox
                                        v-model:checked="form.showName"
                                        name="showName"
                                    />
                                    <span class="ml-2 text-sm text-gray-600"
                                        >あなたのハンドルネームを登録者として表示しますか？</span
                                    >
                                </label>
                            </div>
                        </div>
                        <div class="mx-auto mt-12 w-fit">
                            <p
                                v-if="form.handleName == ''"
                                class="animate-bounce text-center text-red-600"
                            >
                                ハンドルネームを入力してください。（データ操作時に必要です。）
                            </p>
                            <button
                                class="mx-auto flex w-fit items-center rounded-xl bg-[#c20063] py-3 px-12 text-3xl text-white shadow-sm shadow-[#550341]"
                                @click="registerVideo"
                            >
                                Launch
                            </button>
                        </div>
                    </div>
                    <div
                        v-if="!showRegisterRange"
                        class="mx-auto mt-2 flex w-fit flex-col"
                    >
                        <button
                            class="mt-6 flex flex-row items-center rounded-md bg-ptr-dark-brown px-8 py-4 text-2xl text-white"
                            @click="registerPart"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="mx-2 h-8 w-8 fill-white p-1"
                                viewBox="0 0 640 512"
                            >
                                <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                <path
                                    d="M32 64c17.7 0 32 14.3 32 32l0 320c0 17.7-14.3 32-32 32s-32-14.3-32-32V96C0 78.3 14.3 64 32 64zm214.6 73.4c12.5 12.5 12.5 32.8 0 45.3L205.3 224l229.5 0-41.4-41.4c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l96 96c12.5 12.5 12.5 32.8 0 45.3l-96 96c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L434.7 288l-229.5 0 41.4 41.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0l-96-96c-12.5-12.5-12.5-32.8 0-45.3l96-96c12.5-12.5 32.8-12.5 45.3 0zM640 96V416c0 17.7-14.3 32-32 32s-32-14.3-32-32V96c0-17.7 14.3-32 32-32s32 14.3 32 32z"
                                />
                            </svg>
                            範囲を指定して登録
                        </button>
                    </div>
                </div>
                <div
                    v-if="step == 2"
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
                            @click="otherRegister"
                            class="my-6 rounded-md bg-ptr-dark-brown px-6 py-3 text-ptr-white"
                        >
                            別の動画を登録する
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
