<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, computed, onMounted, onUnmounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import YouTube from "vue3-youtube";

defineProps({
    today: Object,
    month: Object,
    other: Object,
});
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

const step = ref(0);
const inputUrl = ref("");
const videoId = ref("");

const isFloatWindow = ref(false);
const youtubeWindowRef = ref(null);

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

        <div>
            <div class="mx-auto pt-4">
                <div v-if="step != 0" class="ml-2 w-fit mb-6 px-8">
                    <button
                        class="text-lg py-2 px-6 bg-gray-800 text-white rounded-lg flex items-center"
                        @click="prevStep()"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 256 512"
                            class="w-5 h-5 fill-white"
                        >
                            <!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                            <path
                                d="M9.4 278.6c-12.5-12.5-12.5-32.8 0-45.3l128-128c9.2-9.2 22.9-11.9 34.9-6.9s19.8 16.6 19.8 29.6l0 256c0 12.9-7.8 24.6-19.8 29.6s-25.7 2.2-34.9-6.9l-128-128z"
                            />
                        </svg>
                        Back
                    </button>
                </div>
                <div class="w-full relative px-6" ref="youtubeWindowRef">
                    <template v-if="step != 0">
                        <div
                            class="aspect-video bg-zinc-800"
                            :class="{
                                'w-full': !isFloatWindow,
                                'w-96': isFloatWindow,
                                absolute: isFloatWindow,
                                'top-0': isFloatWindow,
                                'right-0': isFloatWindow,
                                'z-50': isFloatWindow,
                                'mt-6': isFloatWindow,
                                'p-5': isFloatWindow,
                                'rounded-bl-xl': isFloatWindow,
                            }"
                        >
                            <YouTube
                                :src="videoId"
                                ref="youtube"
                                width="100%"
                                class="videoWindow"
                            />
                        </div>
                    </template>
                </div>
                <div v-if="step == 0" class="mt-12 px-6">
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
                                class="text-3xl py-3 px-12 bg-[#c20063] text-white rounded-xl flex items-center shadow-lg shadow-[#550341]"
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
                <div v-if="step == 1">
                    {{ videoId }}
                    <div class="min-h-screen"></div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
