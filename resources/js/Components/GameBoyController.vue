<script setup>
import { onMounted, onUnmounted } from "vue";
import { install, uninstall } from "@github/hotkey";
import CrossButton from "./ControllerElements/CrossButton.vue";
defineProps({
    focus: Boolean,
    snap: Boolean,
});

const emits = defineEmits([
    "brushNext",
    "brushPrev",
    "focusToggle",
    "LowerRange",
    "HigherRange",
    "undoOperate",
    "addTime",
    "snapToggle",
]);
const brushNext = () => {
    emits("brushNext");
};
const brushPrev = () => {
    emits("brushPrev");
};
const focusToggle = () => {
    emits("focusToggle");
};
const snapToggle = () => {
    emits("snapToggle");
};
const LowerRange = () => {
    emits("LowerRange");
};
const HigherRange = () => {
    emits("HigherRange");
};
const undoOperate = () => {
    emits("undoOperate");
};
const addTime = () => {
    emits("addTime");
};

onMounted(() => {
    for (const el of document.querySelectorAll("[data-hotkey]")) {
        install(el);
    }
});
onUnmounted(() => {
    for (const el of document.querySelectorAll("[data-hotkey]")) {
        uninstall(el);
    }
});
</script>
<style scoped>
/* .round_button {
    background: radial-gradient(
        circle at 30% 10%,
        #faf4fa 2%,
        #a096a0 5%,
        #2d2a2d 15%
    );
} */
.round_button::after {
    content: "";
    position: absolute;
    width: 100%;
    height: 80%;
    top: 0%;
    left: 0%;
    border-radius: 100%;
    background: radial-gradient(
        circle at 30% 10%,
        rgba(225, 225, 225, 0.3),
        rgba(225, 225, 225, 0.2) 50%,
        transparent 55%
    );
}
.cross_wrapper {
    background: radial-gradient(
        circle at 70% 90%,
        #fff2f6 10%,
        transparent 60%,
        #2d2a2d 95%
    );
}
.cross_wrapper::after {
    border-radius: 100%;
    background: radial-gradient(
        circle at 50% 50%,
        transparent 60%,
        #fcf5f7 85%
    );
    pointer-events: none;
}
</style>
<template>
    <div>
        <div class="flex h-fit w-full justify-around">
            <div
                class="cross_wrapper relative box-content w-1/5 rounded-full border-2 border-[#fcf5f7] p-2 after:absolute after:top-0 after:left-0 after:h-full after:w-full after:rounded-full"
            >
                <CrossButton
                    @top="HigherRange"
                    @bottom="LowerRange"
                    @left="brushPrev"
                    @right="brushNext"
                    class="w-full"
                    :backCol="'transparent'"
                ></CrossButton>
            </div>
            <div class="w-1/5">
                <div class="relative h-full w-full">
                    <div
                        class="absolute bottom-0 left-0 flex h-4/5 w-1/2 rotate-45 flex-col items-center justify-around rounded-full border-2 border-[#fcf5f7] bg-gradient-to-r from-custom-shadow/50 via-[#ffedf3] to-[#fff2f6]"
                    >
                        <div
                            class="mx-0 aspect-square h-2/5 -rotate-45 rounded-full"
                        >
                            <button
                                data-hotkey="a"
                                class="round_button relative flex aspect-square w-full items-center justify-center rounded-full bg-ptr-dark-pink shadow-sm shadow-gray-900"
                                @click="addTime"
                            >
                                <svg
                                    class="h-1/3 w-1/3 fill-[#e1dcd8]"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 448 512"
                                >
                                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <path
                                        d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"
                                    />
                                </svg>
                            </button>
                        </div>
                        <div
                            class="mx-0 aspect-square h-2/5 -rotate-45 rounded-full"
                        >
                            <button
                                data-hotkey="b"
                                @click="undoOperate"
                                class="round_button relative flex aspect-square w-full items-center justify-center rounded-full bg-ptr-dark-pink shadow-sm shadow-gray-900"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 512 512"
                                    class="h-1/3 w-1/3 fill-[#e1dcd8]"
                                >
                                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <path
                                        d="M48.5 224H40c-13.3 0-24-10.7-24-24V72c0-9.7 5.8-18.5 14.8-22.2s19.3-1.7 26.2 5.2L98.6 96.6c87.6-86.5 228.7-86.2 315.8 1c87.5 87.5 87.5 229.3 0 316.8s-229.3 87.5-316.8 0c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0c62.5 62.5 163.8 62.5 226.3 0s62.5-163.8 0-226.3c-62.2-62.2-162.7-62.5-225.3-1L185 183c6.9 6.9 8.9 17.2 5.2 26.2s-12.5 14.8-22.2 14.8H48.5z"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex h-fit w-full justify-center">
            <div class="my-16 flex h-fit w-1/3 justify-around">
                <div class="w-1/2">
                    <div
                        class="flex h-fit w-4/5 -rotate-[30deg] justify-center rounded-full p-1 shadow-inner shadow-custom-shadow"
                    >
                        <button
                            @click="focusToggle"
                            class="aspect-[4/1] w-full rounded-full bg-gradient-to-b from-[#797179] to-ptr-dark-brown"
                        ></button>
                        <span
                            class="absolute left-1/2 bottom-0 h-fit w-fit -translate-x-1/2 translate-y-full text-center text-xs"
                            >Focus :
                            {{ focus === true ? "ON" : "OFF" }}
                        </span>
                    </div>
                </div>
                <div class="w-1/2">
                    <div
                        class="flex h-fit w-4/5 -rotate-[30deg] justify-center rounded-full p-1 shadow-inner shadow-custom-shadow"
                    >
                        <button
                            @click="snapToggle"
                            class="aspect-[4/1] w-full rounded-full bg-gradient-to-b from-[#797179] to-ptr-dark-brown"
                        ></button>
                        <span
                            class="absolute left-1/2 bottom-0 h-fit w-fit -translate-x-1/2 translate-y-full text-center text-xs"
                            >Snap :
                            {{ snap === true ? "ON" : "OFF" }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
