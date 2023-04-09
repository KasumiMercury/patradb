<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import YouTube from "vue3-youtube";
import pkg from "lodash";
const { debounce, sortedIndex } = pkg;

const props = defineProps({
    videoId: String,
    timeArray: Array,
});

const youtube = ref(null);
const ytStatus = ref(false);
const currentTime = ref(0);
const interval = ref(null);
const float = ref(false);
const wrapperRef = ref(null);
const isComplex = ref(props.timeArray.length > 3 ? true : false);
const triggerTime = ref(props.timeArray.length > 3 ? props.timeArray[1] : null);
const jumpTime = ref(props.timeArray.length > 3 ? props.timeArray[2] : null);

// console.log(props.timeArray);

onMounted(() => {
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                float.value = false;
            } else {
                float.value = true;
            }
        });
    });
    observer.observe(wrapperRef.value);
});

const play = () => {
    youtube.value.playVideo();
};
const pause = () => {
    youtube.value.pauseVideo();
};
const seek = (time) => {
    youtube.value.seekTo(time, true);
    findTimeSetting();
};

// custom time seek methods
const checkTime = () => {
    currentTime.value = parseInt(youtube.value.getCurrentTime(), 10);
    console.log(currentTime.value, triggerTime.value, jumpTime.value);
    if (currentTime.value > triggerTime.value) {
        seek(jumpTime.value);
    }
};
const findTimeSetting = () => {
    let index = sortedIndex(props.timeArray, currentTime.value);
    if (currentTime.value <= props.timeArray[0]) {
        index = 0;
    }
    if (index < props.timeArray.length - 2) {
        triggerTime.value = props.timeArray[index + 1];
        jumpTime.value = props.timeArray[index + 2];
    } else {
        triggerTime.value = props.timeArray[props.timeArray.length - 1];
    }
    console.log("timeset");
};
const stateChange = () => {
    console.log("stateChange");
    if (youtube.value.getPlayerState() == 1) {
        ytStatus.value = true;
        if (isComplex) {
            findTimeSetting();
            interval.value = setInterval(function () {
                checkTime();
            }, 1000);
        }
    } else {
        ytStatus.value = false;
        clearInterval(interval.value);
    }
};

onBeforeUnmount(() => {
    clearInterval(interval.value);
});
</script>
<style scoped>
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
    <div class="mx-auto aspect-video max-w-5xl" ref="wrapperRef">
        <div
            :class="{
                'my-0 aspect-video w-full': !float,
                'fixed bottom-0 right-0 z-50 mb-12 rounded-tl-xl bg-zinc-800 pt-3 pl-3 pr-2 shadow-xl pb-safe lg:mb-0 lg:pb-0':
                    float,
            }"
        >
            <div
                class="bg-zinc-800"
                :class="{
                    'h-full w-full rounded-t-md p-3 lg:rounded-md': !float,
                    'aspect-video w-64 md:w-96': float,
                }"
            >
                <YouTube
                    :src="videoId"
                    ref="youtube"
                    width="100%"
                    class="videoWindow"
                    :vars="{
                        controls: 1,
                        modestbranding: 1,
                        rel: 0,
                        iv_load_policy: 3,
                        start: timeArray[0],
                        end: timeArray[timeArray.length - 1],
                    }"
                    @state-change="stateChange"
                />
            </div>
            <div v-if="float" class="w-full pb-4 pt-4">
                <slot
                    name="activator"
                    v-bind:play="play"
                    :pause="pause"
                    v-bind:ytStatus="ytStatus"
                />
            </div>
        </div>
        <div
            v-if="!float"
            class="mx-auto w-full max-w-xl rounded-b-xl bg-zinc-800 px-4 py-1"
        >
            <slot
                name="activator"
                v-bind:play="play"
                :pause="pause"
                v-bind:ytStatus="ytStatus"
            />
        </div>
    </div>
</template>
