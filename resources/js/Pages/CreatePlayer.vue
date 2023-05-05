<script setup>
import { Head, Link, usePage, useForm, router } from "@inertiajs/vue3";
import { ref, computed, onMounted, inject, watch } from "vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import YouTubeEditwindow from "@/Components/YouTubeEditwindow.vue";
import YouTubeResponsive from "@/Components/YouTubeResponsive.vue";
import InteractiveTimeline from "@/Components/InteractiveTimeline.vue";

const $cookies = inject("$cookies");

const props = defineProps({
    videoId: String,
});

const step = ref(0);
const inputUrl = ref("");
const videoId = ref("0kNH45w23aA");
const videoLength = ref(3600);
const currentTime = ref(20);

const isFloatWindow = ref(false);
const youtubeWindowRef = ref(null);
const youtubeComponent = ref();

const isTransfer = ref(false);
const backedStep = ref(false);

const timeArray = ref([]);
let sendTimeArray = {};

const form = useForm({
    videoId: "",
    title: "",
    start: null,
    end: null,
    middle: "",
    handleName: "",
    public: true,
    showName: true,
});

if (usePage().props.auth.user !== null) {
    form.handleName = usePage().props.auth.user.name;
    if (usePage().props.auth.user.role == "admin") {
        form.showName = false;
    }
}

if (props.videoId) {
    videoId.value = props.videoId;
    // console.log(props.videoId)
}

onMounted(() => {
    mounted.value = true;
    if (props.videoId) {
        timelineStep();
    }
});

const timelineStep = () => {
    if (videoId.value != "") {
        step.value = 1;
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
        $cookies.set("playerParent", { videoId: videoId.value });
        isTransfer.value = false;
    }
};

const existCookies = () => {
    let cookiesIsExist = $cookies.isKey("playerParent");
    return cookiesIsExist;
};
const loadCookies = () => {
    let temp = $cookies.get("playerParent");
    videoId.value = temp["videoId"];
    isTransfer.value = true;
    step.value = 1;
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
};

const prevStep = () => {
    backedStep.value = true;
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
    timelineStep();
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

const setVideoLength = (length) => {
    videoLength.value = length;
};

const setCurrentTime = (time) => {
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

const launchStep = (data) => {
    timeArray.value = data;
    step.value = 2;
};

const submit = () => {
    form.videoId = videoId.value;
    form.start = sendTimeArray["start"];
    form.end = sendTimeArray["end"];
    form.middle = sendTimeArray["middle"];
    form.post(route("post.player"), {
        onFinish: () => {
            $cookies.remove("playerParent");
            $cookies.remove("playerTimeArray");
        },
    });
};

watch(
    () => timeArray.value,
    (val) => {
        sendTimeArray["start"] = val[0];
        sendTimeArray["end"] = val[val.length - 1];
        if (val.length > 3) {
            let tempArray = val.slice(1, -1);
            sendTimeArray["middle"] = tempArray.join(",");
        } else {
            sendTimeArray["middle"] = "";
        }
    }
);
</script>
<script>
import AppLayout from "../Layouts/AppLayout.vue";

export default {
    layout: AppLayout,
};
</script>
<style scoped>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease, transform 0.5s ease;
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
.launchButton {
    /* bacground radial gradiation dark pink to light pink */
    background: radial-gradient(circle at 50% 50%, #d8346e 0%, #c20063 80%);
    position: relative;
}
.launchButton::after {
    position: absolute;
    width: 100%;
    height: 100%;
    content: "";
    top: 0;
    left: 0;
    border-radius: 9999px;
    /* radial shine effect */
    background: radial-gradient(
        circle at 30% 30%,
        rgba(255, 255, 255, 0.4) 0%,
        rgba(255, 255, 255, 0) 50%
    );
}
</style>
<template>
    <Head>
        <title>LaunchMemory</title>
    </Head>
    <Teleport to='[data-slot="header"]' v-if="mounted">
        <p class="text-xs font-semibold text-gray-800">
            <Link :href="route('adddata')" class="underline">Launch</Link> /
            LaunchMemory
        </p>
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
                    <div class="aspect-video w-full" v-if="step == 1">
                        <YouTubeEditwindow
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
                <div v-if="step == 1">
                    <div class="mb-12 mt-0 min-h-screen w-full">
                        <InteractiveTimeline
                            :isTransfer="isTransfer"
                            :videoLength="videoLength"
                            :currentTime="currentTime"
                            :backedStep="backedStep"
                            @playAt="timelinePlayAt"
                            @launch="launchStep"
                        ></InteractiveTimeline>
                    </div>
                </div>
                <div v-if="step == 2">
                    <YouTubeResponsive
                        ref="youtubeComponent"
                        :timeArray="timeArray"
                        :videoId="videoId"
                    >
                        <template v-slot:activator="{ play, pause, ytStatus }">
                            <div class="flex justify-around pb-1">
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
                            </div>
                        </template>
                    </YouTubeResponsive>
                    <form @submit.prevent="submit">
                        <div class="mx-auto w-full px-6 pb-64">
                            <div class="my-16 block">
                                <div class="mx-auto mt-4 max-w-7xl">
                                    <InputLabel
                                        for="dataTitle"
                                        value="Data Title"
                                    />
                                    <TextInput
                                        id="dataTitle"
                                        v-model="form.title"
                                        type="text"
                                        class="mt-1 block w-full"
                                        required
                                        autofocus
                                    />
                                </div>
                                <div
                                    class="mx-auto mt-12 max-w-xl"
                                    v-if="!$page.props.auth.user"
                                >
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

                                <div class="mx-auto mt-12 w-fit">
                                    <label class="flex items-center">
                                        <Checkbox
                                            v-model:checked="form.public"
                                            name="public"
                                        />
                                        <span class="ml-2 text-sm text-gray-600"
                                            >このデータを公開設定にしますか？</span
                                        >
                                    </label>
                                </div>

                                <div
                                    class="mx-auto mt-12 w-fit"
                                    v-if="form.public"
                                >
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
                            <div class="animate-bounce">
                                <p
                                    v-if="form.title == ''"
                                    class="text-center text-red-600"
                                >
                                    データのタイトルを設定してください。
                                </p>

                                <p
                                    v-if="form.handleName == ''"
                                    class="text-center text-red-600"
                                >
                                    ハンドルネームを入力してください。（データ操作時に必要です。）
                                </p>
                            </div>
                            <transition>
                                <div
                                    class="mx-auto mt-12 w-fit"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                    v-if="
                                        form.title != '' &&
                                        form.handleName != ''
                                    "
                                >
                                    <button
                                        type="submit"
                                        class="launchButton mx-auto flex aspect-square w-fit flex-col items-center justify-center whitespace-nowrap rounded-full py-1 px-3 font-Abril text-xl text-white shadow-md shadow-[#550341] lg:py-2 lg:px-6 lg:text-3xl"
                                    >
                                        Launch
                                        <svg
                                            fill="none"
                                            stroke="currentColor"
                                            class="h-8 w-8 animate-pulse stroke-white lg:h-10 lg:w-10"
                                            stroke-width="1.5"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"
                                            ></path>
                                        </svg>
                                    </button>
                                </div>
                            </transition>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
