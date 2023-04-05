<script setup>
import { ref, computed, onBeforeUnmount } from "vue";
import YouTube from "vue3-youtube";

defineProps({
    videoId: String,
    isFloatWindow: Boolean,
});
const emits = defineEmits(["getVideoLength", "currentTimeUpdate"]);

const youtube = ref(null);
const ytStatus = ref(false);
const currentTime = ref(0);
const interval = ref(null);

const getVideoLength = () => {
    let videoLength = youtube.value.getDuration();
    emits("getVideoLength", videoLength);
};

const play = () => {
    youtube.value.playVideo();
};
const pause = () => {
    youtube.value.pauseVideo();
};
const seekDiff = (data) => {
    youtube.value.seekTo(currentTime.value + data, true);
    currentTime.value = parseInt(youtube.value.getCurrentTime(), 10);
};
const playAt = (time) => {
    youtube.value.seekTo(time);
};

const stateChange = () => {
    console.log("stateChange");
    if (youtube.value.getPlayerState() == 1) {
        ytStatus.value = true;
        interval.value = setInterval(function () {
            currentTime.value = parseInt(youtube.value.getCurrentTime(), 10);
            emits("currentTimeUpdate", currentTime.value);
        }, 1000);
    } else {
        ytStatus.value = false;
        clearInterval(interval.value);
    }
};

onBeforeUnmount(() => {
    clearInterval(interval.value);
});
defineExpose({
    playAt,
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
    <div
        :class="{
            'my-0 aspect-video w-full': !isFloatWindow,
            'fixed bottom-0 right-0 z-50 mb-12 rounded-tl-xl bg-zinc-800 pt-3 pl-3 pr-2 shadow-xl pb-safe lg:mb-0 lg:pb-0':
                isFloatWindow,
        }"
    >
        <div
            class="bg-zinc-800"
            :class="{
                'h-full w-full': !isFloatWindow,
                'aspect-video w-64 md:w-96 ': isFloatWindow,
            }"
        >
            <YouTube
                :src="videoId"
                ref="youtube"
                width="100%"
                class="videoWindow"
                @ready="getVideoLength"
                @state-change="stateChange"
            />
        </div>
        <div v-if="isFloatWindow" class="w-full pb-4 pt-4">
            <slot
                name="activator"
                v-bind:play="play"
                :pause="pause"
                :seek="seekDiff"
                v-bind:ytStatus="ytStatus"
            />
        </div>
    </div>
    <div
        v-if="!isFloatWindow"
        class="mx-auto w-full max-w-xl bg-zinc-800 px-4 py-1"
    >
        <slot
            name="activator"
            v-bind:play="play"
            :pause="pause"
            :seek="seekDiff"
            v-bind:ytStatus="ytStatus"
        />
    </div>
</template>
