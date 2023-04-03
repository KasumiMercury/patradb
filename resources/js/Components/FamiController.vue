<script setup>
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
    background-color: #c20063;
    width: 60%;
    height: 40%;
    z-index: 40;
    border-radius: 1rem;
}
.controller_gold {
    position: relative;
}
.controller_gold::after {
    content: "";
    position: absolute;
    bottom: 20px;
    left: 0;
    background-color: #f6e0af;
    width: 70%;
    height: 100%;
    z-index: 5;
    border-radius: 1rem;
}
.round_button {
    background: radial-gradient(circle at 30% 10%, #faf4fa 2%, #2d2a2d 20%);
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
        transparent 60%
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
</style>
<template>
    <div class="w-full">
        <div class="controller mx-auto mb-12 w-full max-w-7xl px-6">
            <div
                class="controller_body aspect-[20/7] w-full rounded-3xl bg-[#c20063]"
            >
                <div
                    class="absolute bottom-0 left-0 ml-0 h-full w-2/5 py-5 pl-5"
                >
                    <div class="h-full w-full rounded-2xl bg-[#f6e0af]"></div>
                </div>
                <div class="absolute bottom-0 h-3/5 w-full px-5 pb-5">
                    <div
                        class="controller_gold h-full w-full rounded-2xl bg-[#f6e0af]"
                    ></div>
                    <div
                        class="absolute bottom-1/3 left-0 z-10 flex h-4/5 w-1/3 flex-row"
                    >
                        <div class="relative z-10 mx-auto aspect-square h-full">
                            <div
                                class="absolute top-0 left-1/2 box-content aspect-square w-1/3 -translate-x-1/2 px-2 pt-2 after:absolute after:left-0 after:top-0 after:z-[-10] after:h-full after:w-full after:rounded-t-xl after:bg-[#c20063]"
                            >
                                <button
                                    @click="HigherRange"
                                    class="relative box-content flex aspect-square w-full items-center justify-center rounded-t-md border-r-2 border-t-2 border-gray-600 bg-gradient-to-l from-[#515151] via-[#2d2a2d] to-[#1d1d1d] after:absolute after:bottom-0 after:left-0 after:h-2 after:w-full after:translate-y-1/2 after:bg-gradient-to-l after:from-[#515151] after:via-[#2d2a2d] after:to-[#1d1d1d]"
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
                                class="absolute top-1/2 left-0 box-content aspect-square w-1/3 -translate-y-1/2 py-2 pr-2 after:absolute after:right-0 after:top-0 after:z-[-10] after:mr-px after:h-full after:w-full after:-translate-x-2 after:rounded-l-xl after:bg-[#c20063]"
                            >
                                <button
                                    @click="brushPrev"
                                    class="relative box-content flex aspect-square w-full items-center justify-center rounded-l-md border-t-2 border-gray-600 bg-[#1d1d1d] after:absolute after:bottom-0 after:right-0 after:h-full after:w-2 after:translate-x-1/2 after:bg-[#1d1d1d]"
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
                                class="absolute top-1/2 right-0 box-content aspect-square w-1/3 -translate-y-1/2 py-2 pl-1 after:absolute after:left-0 after:top-0 after:z-[-15] after:ml-2 after:h-full after:w-full after:translate-x-1 after:rounded-r-xl after:bg-[#c20063]"
                            >
                                <button
                                    @click="brushNext"
                                    class="relative box-content flex aspect-square w-full items-center justify-center rounded-r-md border-t-2 border-r-2 border-gray-400 bg-[#515151] after:absolute after:bottom-0 after:left-0 after:h-full after:w-2 after:-translate-x-1/2 after:bg-[#515151]"
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
                                class="absolute bottom-0 right-1/2 box-content aspect-square w-1/3 translate-x-1/2 px-2 pb-1 after:absolute after:left-0 after:bottom-0 after:z-[-10] after:h-full after:w-full after:translate-y-1 after:rounded-b-xl after:bg-[#c20063]"
                            >
                                <button
                                    class="relative box-content flex aspect-square w-full items-center justify-center rounded-b-md border-r-2 border-gray-200 bg-gradient-to-l from-[#515151] via-[#2d2a2d] to-[#1d1d1d] after:absolute after:top-0 after:left-0 after:h-2 after:w-full after:-translate-y-1/2 after:bg-gradient-to-l after:from-[#515151] after:via-[#2d2a2d] after:to-[#1d1d1d]"
                                    @click="LowerRange"
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
                                    class="round_ellipse_button h-full w-full rounded-full text-[#e1dcd8] shadow-sm shadow-gray-900"
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
                                    class="round_ellipse_button h-full w-full rounded-full text-[#e1dcd8] shadow-sm shadow-gray-900"
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
                                @click="undoOperate"
                                class="round_button relative flex aspect-square w-full items-center justify-center rounded-full shadow-sm shadow-gray-900"
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
                                class="round_button relative flex aspect-square w-full items-center justify-center rounded-full shadow-sm shadow-gray-900"
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
</template>
