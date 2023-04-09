<script setup>
import { onMounted, onUnmounted } from "vue";
import { install, uninstall } from "@github/hotkey";
defineProps({ backCol: String });
const emits = defineEmits(["top", "bottom", "left", "right"]);
const top = () => {
    emits("top");
};
const bottom = () => {
    emits("bottom");
};
const left = () => {
    emits("left");
};
const right = () => {
    emits("right");
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
.center_radial {
    background: radial-gradient(circle at 50% 50%, #2d2a2d 20%, #030712 100%);
    position: relative;
}
.center_radial::before {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0%;
    left: 0%;
    border-radius: 100%;
    background: radial-gradient(circle at 40% 60%, #faf4fa 2%, transparent 20%);
}
.center_radial::after {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0%;
    left: 0%;
    border-radius: 100%;
    background: conic-gradient(
        from 200deg at 40% 60%,
        transparent 0deg 15deg,
        rgba(235, 224, 230, 0.5) 20deg 20deg,
        transparent 30deg 40deg,
        rgba(235, 224, 230, 0.5) 50deg 70deg,
        transparent 80deg 360deg
    );
}
.cross_after::after {
    background-color: v-bind(backCol);
}
</style>
<template>
    <div class="relative aspect-square">
        <div
            class="cross_after absolute top-0 left-1/2 box-content aspect-square w-1/3 -translate-x-1/2 -translate-y-1 px-2 pt-2 after:absolute after:left-0 after:top-0 after:z-[-10] after:h-full after:w-full after:rounded-t-xl"
        >
            <button
                data-hotkey="ArrowUp"
                @click="top"
                class="relative box-content flex aspect-square h-full w-full items-center justify-center rounded-t-md border-r-2 border-t-2 border-gray-600 bg-gradient-to-l from-[#515151] via-[#2d2a2d] to-[#1d1d1d] after:absolute after:bottom-0 after:left-0 after:h-2 after:w-full after:translate-y-1/2 after:bg-gradient-to-l after:from-[#515151] after:via-[#2d2a2d] after:to-[#1d1d1d]"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"
                    class="h-1/3 w-1/3 fill-[#e1dcd8] stroke-[#e1dcd8]"
                >
                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path
                        d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM184 296c0 13.3 10.7 24 24 24s24-10.7 24-24V232h64c13.3 0 24-10.7 24-24s-10.7-24-24-24H232V120c0-13.3-10.7-24-24-24s-24 10.7-24 24v64H120c-13.3 0-24 10.7-24 24s10.7 24 24 24h64v64z"
                    />
                </svg>
            </button>
        </div>
        <div
            class="cross_after absolute top-1/2 left-0 box-content aspect-square w-1/3 -translate-y-1/2 py-2 pr-2 after:absolute after:right-0 after:top-0 after:z-[-10] after:mr-px after:h-full after:w-full after:-translate-x-2 after:rounded-l-xl"
        >
            <button
                data-hotkey="ArrowLeft"
                @click="left"
                class="relative box-content flex aspect-square h-full w-full items-center justify-center rounded-l-md border-t-2 border-gray-600 bg-[#1d1d1d] after:absolute after:bottom-0 after:right-0 after:h-full after:w-2 after:translate-x-1/2 after:bg-[#1d1d1d]"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 256 512"
                    class="h-1/2 w-1/2 fill-[#e1dcd8] stroke-[#e1dcd8]"
                >
                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path
                        d="M9.4 278.6c-12.5-12.5-12.5-32.8 0-45.3l128-128c9.2-9.2 22.9-11.9 34.9-6.9s19.8 16.6 19.8 29.6l0 256c0 12.9-7.8 24.6-19.8 29.6s-25.7 2.2-34.9-6.9l-128-128z"
                    />
                </svg>
            </button>
        </div>
        <div
            class="absolute top-1/2 right-1/2 z-10 flex aspect-square w-1/3 -translate-y-1/2 translate-x-1/2 items-center justify-center bg-gradient-to-l from-[#515151] via-[#2d2a2d] to-[#1d1d1d]"
        >
            <div
                class="center_radial aspect-square w-2/3 rounded-full border-2 border-gray-900 shadow-md shadow-gray-900"
            ></div>
        </div>
        <div
            class="cross_after absolute top-1/2 right-0 box-content aspect-square w-1/3 -translate-y-1/2 py-2 pl-2 after:absolute after:left-0 after:top-0 after:z-[-15] after:ml-2 after:h-full after:w-full after:translate-x-1 after:rounded-r-xl"
        >
            <button
                data-hotkey="ArrowRight"
                @click="right"
                class="relative box-content flex aspect-square h-full w-full items-center justify-center rounded-r-md border-t-2 border-r-2 border-gray-400 bg-[#515151] after:absolute after:bottom-0 after:left-0 after:h-full after:w-2 after:-translate-x-1/2 after:bg-[#515151]"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 256 512"
                    class="h-1/2 w-1/2 fill-[#e1dcd8] stroke-[#e1dcd8]"
                >
                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path
                        d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"
                    />
                </svg>
            </button>
        </div>
        <div
            class="cross_after absolute bottom-0 right-1/2 box-content aspect-square w-1/3 translate-x-1/2 translate-y-px px-2 pb-1 pt-px after:absolute after:left-0 after:bottom-0 after:z-[-10] after:h-full after:w-full after:translate-y-1 after:rounded-b-xl"
        >
            <button
                data-hotkey="ArrowDown"
                class="relative m-0 box-content flex aspect-square h-full w-full items-center justify-center rounded-b-md border-r-2 border-gray-200 bg-gradient-to-l from-[#515151] via-[#2d2a2d] to-[#1d1d1d] after:absolute after:top-0 after:left-0 after:h-2 after:w-full after:-translate-y-1/2 after:bg-gradient-to-l after:from-[#515151] after:via-[#2d2a2d] after:to-[#1d1d1d]"
                @click="bottom"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"
                    class="h-1/3 w-1/3 fill-[#e1dcd8] stroke-[#e1dcd8]"
                >
                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path
                        d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM136 184c-13.3 0-24 10.7-24 24s10.7 24 24 24H280c13.3 0 24-10.7 24-24s-10.7-24-24-24H136z"
                    />
                </svg>
            </button>
        </div>
    </div>
</template>
