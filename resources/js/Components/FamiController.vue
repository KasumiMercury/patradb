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
.controller_body {
    position: relative;
}
.controller_body::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    /* background-image gradiation shine effect slanting */
    background-image: linear-gradient(
        330deg,
        transparent 25%,
        rgba(255, 255, 255, 0.4) 39%,
        rgba(255, 255, 255, 0.4) 40%,
        transparent 45%,
        transparent 55%,
        rgba(255, 255, 255, 0.4) 69%,
        rgba(255, 255, 255, 0.4) 70%,
        transparent 75%,
        transparent
    );
    /* background-blend-mode: luminosity; */
    mix-blend-mode: hard-light;
    width: 100%;
    height: 100%;
    z-index: 5;
    border-radius: 1.5rem;
    pointer-events: none;
}
.controller_body::before {
    content: "";
    position: absolute;
    top: 2%;
    right: 0;
    background-color: #a00354;
    width: 100%;
    height: 100%;
    z-index: -10;
    border-radius: 1.5rem;
}
.gold_bottom {
    position: relative;
}
.gold_bottom::after {
    content: "";
    position: absolute;
    bottom: 20%;
    left: 0;
    background-color: #dfcfab;
    width: 70%;
    height: 100%;
    z-index: 1;
    border-radius: 1rem;
}
.round_button {
    background: radial-gradient(
        circle at 30% 10%,
        #faf4fa 2%,
        #a096a0 5%,
        #2d2a2d 15%
    );
}
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
.round_ellipse_button {
    /* background: linear-gradient(to bottom, #faf4fa 2%, #2d2a2d 20%); */
    background-color: #2d2a2d;
    border-width: 0.21rem;
    border-color: rgba(194, 183, 194, 0.1);
}
.rounded_ellipse_wrap {
    width: 35%;
    height: 60%;
}
.rounded_ellipse_wrap::after {
    width: 100%;
    height: 100%;
    box-sizing: content-box;
    border-width: 4px;
}
.ellipse_buttons_wrap {
    background: linear-gradient(to bottom, #75003b 40%, rgb(194, 0, 99) 60%);
}
.ellipse_buttons_wrap::before {
    content: "";
    position: absolute;
    width: calc(100% / 3);
    height: 100%;
    margin: auto;
    border-radius: 9999px;
    z-index: -10;
    background-image: conic-gradient(
        from 50deg at 80% 50%,
        transparent 0deg 15deg,
        rgba(235, 224, 230, 0.5) 20deg 30deg,
        transparent 50deg 60deg,
        rgba(235, 224, 230, 0.5) 90deg 100deg,
        transparent 120deg 360deg
    );
}
.ellipse_buttons_wrap::after {
    content: "";
    position: absolute;
    width: calc(100% / 3);
    height: 100%;
    margin: auto;
    border-radius: 9999px;
    z-index: -10;
    background-image: conic-gradient(
        from 220deg at 20% 50%,
        transparent 0deg 5deg,
        hsl(329, 20%, 90%) 20deg 25deg,
        transparent 40deg 360deg
    );
}
.gold_left {
    padding: 2% 0 2% 2%;
}
.gold_bottom_wrapper {
    padding: 0 2% 2% 2%;
}
.gold_wrap {
    position: relative;
    background: none;
}
.gold_wrap::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    background-color: #c20063;
    width: 60%;
    height: 40%;
    z-index: 3;
    border-radius: 1.5rem;
}
.gold_inherit {
    background: inherit;
}
</style>
<style>
.cross_after::after {
    background-color: #c20063;
}
</style>
<template>
    <div class="w-full">
        <div class="controller mx-auto mb-12 w-full max-w-7xl lg:px-6">
            <div
                class="controller_body aspect-[20/7] w-full rounded-3xl bg-[#c20063] after:rounded-3xl"
            >
                <div class="gold_wrap h-full w-full rounded-3xl">
                    <div
                        class="gold_left absolute bottom-0 left-0 ml-0 h-full w-2/5"
                    >
                        <div
                            class="relative h-full w-full rounded-2xl bg-[#dfcfab]"
                        >
                            <div
                                class="absolute top-4 left-4 aspect-square h-fit bg-ptr-dark-brown p-3"
                            >
                                <p class="font-Abril text-2xl text-[#dfcfab]">
                                    Ⅷ
                                </p>
                            </div>
                        </div>
                    </div>
                    <div
                        class="gold_bottom_wrapper absolute bottom-0 h-3/5 w-full"
                    >
                        <div
                            class="gold_bottom h-full w-full rounded-2xl bg-[#dfcfab]"
                        ></div>

                        <div
                            class="absolute bottom-1/3 left-0 z-20 flex h-4/5 w-1/3 flex-row"
                        >
                            <CrossButton
                                class="mx-auto h-full"
                                @top="HigherRange"
                                @bottom="LowerRange"
                                @left="brushPrev"
                                @right="brushNext"
                                :backCol="'#c20063'"
                            ></CrossButton>
                        </div>
                        <!-- center buttons -->
                        <div
                            class="absolute inset-x-0 bottom-1/3 z-10 flex h-2/5 w-full flex-row py-2"
                        >
                            <div
                                class="ellipse_buttons_wrap z-[-3] mx-auto flex h-full w-1/3 items-center justify-around rounded-full border-4 border-[#c20063]"
                            >
                                <div
                                    class="rounded_ellipse_wrap relative rounded-full border-4 border-[#75003b] bg-[#75003b] after:absolute after:top-0 after:right-0 after:z-[-2] after:-translate-y-[4px] after:translate-x-1/2 after:border-[#75003b] after:bg-[#75003b]"
                                >
                                    <button
                                        @click="focusToggle"
                                        class="round_ellipse_button z-40 h-full w-full rounded-full text-[#e1dcd8] shadow-sm shadow-gray-900"
                                    >
                                        <span
                                            class="absolute left-1/2 bottom-0 w-full -translate-x-1/2 translate-y-full text-center"
                                            >Focus :
                                            {{ focus === true ? "ON" : "OFF" }}
                                        </span>
                                        Focus
                                    </button>
                                </div>
                                <div
                                    class="rounded_ellipse_wrap relative rounded-full border-4 border-[#75003b] bg-[#75003b] after:absolute after:top-0 after:left-0 after:z-[-2] after:-translate-y-[4px] after:-translate-x-1/2 after:border-[#75003b] after:bg-[#75003b]"
                                >
                                    <button
                                        @click="snapToggle"
                                        class="round_ellipse_button z-40 h-full w-full rounded-full text-[#e1dcd8] shadow-sm shadow-gray-900"
                                    >
                                        <span
                                            class="absolute left-1/2 bottom-0 w-full -translate-x-1/2 translate-y-full text-center"
                                            >Snap :
                                            {{ snap === true ? "ON" : "OFF" }}
                                        </span>
                                        Snap
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Add Remove roundButtons -->
                        <div
                            class="absolute bottom-1/3 right-0 z-10 mx-5 flex h-fit w-1/3 items-center justify-around px-10"
                        >
                            <div
                                class="aspect-square w-1/3 rounded-full bg-[#c20063] p-2"
                            >
                                <button
                                    data-hotkey="b"
                                    @click="undoOperate"
                                    class="round_button relative z-40 flex aspect-square w-full items-center justify-center rounded-full shadow-sm shadow-gray-900"
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
                            <div
                                class="aspect-square w-1/3 rounded-full bg-[#c20063] p-2"
                            >
                                <button
                                    data-hotkey="a"
                                    class="round_button relative z-40 flex aspect-square w-full items-center justify-center rounded-full shadow-sm shadow-gray-900"
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
