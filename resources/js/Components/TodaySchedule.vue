<script setup>
import dayjs from "dayjs";
import chroma from "chroma-js";

const props = defineProps({
    data: Object,
    persistent: Object,
});

const formatDay = (input) => {
    const day = dayjs(input);
    return day.format("M/DD");
};
const formatTime = (input) => {
    const day = dayjs(input);
    return day.format("HH:mm");
};

const cols = chroma
    .scale(["#c20063", "#D8346E", "#ff99cd"])
    .classes(Object.keys(props.data).length)
    .mode("lch");
</script>

<template>
    <div
        class="relative rounded-t-lg rounded-bl-lg rounded-br-3xl bg-ptr-white px-4 pb-4 before:absolute before:top-2 before:left-2 before:-z-10 before:h-full before:w-full before:rounded-t-lg before:rounded-bl-lg before:rounded-br-3xl before:bg-cyan-500"
    >
        <div class="w-full select-none text-ptr-dark-brown">
            <h1
                class="flex items-center px-6 pt-6 pb-3 text-2xl md:px-12 md:pt-12 md:pb-6"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 448 512"
                    class="mr-2 h-5 w-5 fill-ptr-dark-brown"
                >
                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path
                        d="M224 0c-17.7 0-32 14.3-32 32V51.2C119 66 64 130.6 64 208v18.8c0 47-17.3 92.4-48.5 127.6l-7.4 8.3c-8.4 9.4-10.4 22.9-5.3 34.4S19.4 416 32 416H416c12.6 0 24-7.4 29.2-18.9s3.1-25-5.3-34.4l-7.4-8.3C401.3 319.2 384 273.9 384 226.8V208c0-77.4-55-142-128-156.8V32c0-17.7-14.3-32-32-32zm45.3 493.3c12-12 18.7-28.3 18.7-45.3H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7z"
                    />
                </svg>
                In Progress
            </h1>
        </div>
        <div class="w-full pb-4 lg:px-4">
            <div
                class="flex w-full flex-col justify-center 2xl:flex-row 2xl:flex-wrap"
            >
                <div
                    v-for="(data, index) in props.data"
                    :key="index"
                    class="my-2 mx-auto w-full px-2 lg:my-4 2xl:w-1/2"
                    :class="index % 2 === 0 ? 'ml-0 mr-auto' : 'mr-0 ml-auto'"
                >
                    <div
                        class="flex w-full flex-col justify-start rounded-md shadow shadow-custom-shadow md:flex-row"
                    >
                        <div
                            class="mr-2 flex w-full flex-row items-baseline justify-center gap-2 rounded-t-md py-3 px-4 text-white md:w-16 md:flex-col md:items-center md:gap-0 md:rounded-l-md md:rounded-tr-none lg:mr-4 lg:w-20"
                            :style="
                                'background-color:' +
                                cols(index / Object.keys(props.data).length) +
                                ';'
                            "
                        >
                            <p class="whitespace-nowrap text-lg lg:text-2xl">
                                {{ formatDay(data["end_date"]) }}
                            </p>
                            <p class="whitespace-nowrap text-sm lg:text-lg">
                                {{ formatTime(data["end_date"]) }}
                            </p>
                        </div>
                        <div class="py-2 px-4">
                            <p class="text-md lg:text-xl title-display">
                                {{ data["event_title"] }}
                            </p>
                        </div>
                    </div>
                </div>
                <div
                    v-for="(data, index) in props.persistent"
                    :key="index"
                    class="my-2 mx-auto w-full px-2 lg:my-4 2xl:w-1/2"
                    :class="index % 2 === 0 ? 'ml-0 mr-auto' : 'mr-0 ml-auto'"
                >
                    <div
                        class="flex min-h-[88px] w-full flex-col justify-start rounded-md px-0 shadow shadow-custom-shadow md:flex-row"
                    >
                        <div
                            class="flex w-full flex-col items-center rounded-t-md bg-ptr-dark-brown py-3 px-4 text-white md:mr-4 md:w-16 md:rounded-l-md md:rounded-tr-none lg:w-20"
                        >
                            <p class="whitespace-nowrap text-xs">Permanent</p>
                        </div>
                        <div class="py-2 px-4">
                            <p class="text-md lg:text-xl title-display">
                                {{ data["event_title"] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ml-auto mt-4 mr-12 w-fit">
            <button>通知設定</button>
        </div>
        <div class="absolute top-0 right-0 -translate-y-1/3">
            <svg
                class="h-16 -rotate-[20deg] md:h-36"
                width="100%"
                height="100%"
                viewBox="220 0 680 1080"
                version="1.1"
                xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                xml:space="preserve"
                xmlns:serif="http://www.serif.com/"
                style="
                    fill-rule: evenodd;
                    clip-rule: evenodd;
                    stroke-linecap: round;
                    stroke-linejoin: round;
                    stroke-miterlimit: 1.5;
                "
            >
                <g transform="matrix(1.6925,0,0,1.6925,-191.62,-219.992)">
                    <g id="レイヤー1">
                        <path
                            d="M238.863,398.703C255.253,333.468 289.435,291.961 316.465,264.772C375.177,205.716 461.158,147.671 527.865,132.313C463.707,219.151 423.93,303.703 405.441,386.26L360.752,410.879C325.532,389.045 284.073,387.711 238.863,398.703Z"
                            style="
                                fill: url(#_Linear1);
                                stroke: rgb(54, 4, 15);
                                stroke-width: 0.59px;
                            "
                        />
                        <path
                            d="M564.979,692.974C564.979,692.974 481.729,697.187 475.603,666.75C467.312,625.558 579.42,538.984 664.845,478.705C521.222,507.203 385.03,553.759 261.526,631.127C323.401,634.658 381.452,643.847 394.708,664.378C407.964,684.909 341.061,754.313 341.061,754.313C439.411,775.008 532.806,748.415 564.979,692.974Z"
                            style="
                                fill: url(#_Radial2);
                                stroke: rgb(54, 4, 15);
                                stroke-width: 0.59px;
                            "
                        />
                        <path
                            d="M252.107,472.711C305.247,366.54 389.636,325.416 498.722,335.702C452.831,379.512 422.278,439.106 419.204,466.129C417.913,477.481 437.19,485.658 448.628,484.271C489.568,479.305 607.986,475.033 664.845,478.705C524.957,513.622 390.453,564.24 261.526,631.127C285.43,588.935 326.117,547.103 348.033,532.046C359.967,523.847 366.817,501.011 358.288,489.837C339.29,464.946 295.381,464.733 252.107,472.711Z"
                            style="
                                fill: url(#_Radial3);
                                stroke: rgb(54, 4, 15);
                                stroke-width: 0.59px;
                            "
                        />
                    </g>
                </g>
                <defs>
                    <linearGradient
                        id="_Linear1"
                        x1="0"
                        y1="0"
                        x2="1"
                        y2="0"
                        gradientUnits="userSpaceOnUse"
                        gradientTransform="matrix(-144.715,268.023,-268.023,-144.715,434.1,139.297)"
                    >
                        <stop
                            offset="0"
                            style="
                                stop-color: rgb(131, 29, 66);
                                stop-opacity: 1;
                            "
                        />
                        <stop
                            offset="0.75"
                            style="stop-color: rgb(93, 29, 49); stop-opacity: 1"
                        />
                        <stop
                            offset="1"
                            style="stop-color: rgb(53, 28, 32); stop-opacity: 1"
                        />
                    </linearGradient>
                    <radialGradient
                        id="_Radial2"
                        cx="0"
                        cy="0"
                        r="1"
                        gradientUnits="userSpaceOnUse"
                        gradientTransform="matrix(139.715,153.954,-203.622,184.79,378.696,673.067)"
                    >
                        <stop
                            offset="0"
                            style="stop-color: rgb(52, 28, 34); stop-opacity: 1"
                        />
                        <stop
                            offset="0.62"
                            style="stop-color: rgb(83, 28, 46); stop-opacity: 1"
                        />
                        <stop
                            offset="0.77"
                            style="
                                stop-color: rgb(114, 28, 57);
                                stop-opacity: 1;
                            "
                        />
                        <stop
                            offset="1"
                            style="
                                stop-color: rgb(120, 28, 59);
                                stop-opacity: 1;
                            "
                        />
                    </radialGradient>
                    <radialGradient
                        id="_Radial3"
                        cx="0"
                        cy="0"
                        r="1"
                        gradientUnits="userSpaceOnUse"
                        gradientTransform="matrix(322.624,-45.0197,32.3906,232.12,337.664,526.614)"
                    >
                        <stop
                            offset="0"
                            style="stop-color: rgb(51, 28, 34); stop-opacity: 1"
                        />
                        <stop
                            offset="0.33"
                            style="stop-color: rgb(59, 28, 37); stop-opacity: 1"
                        />
                        <stop
                            offset="0.43"
                            style="stop-color: rgb(83, 28, 46); stop-opacity: 1"
                        />
                        <stop
                            offset="0.5"
                            style="
                                stop-color: rgb(114, 28, 57);
                                stop-opacity: 1;
                            "
                        />
                        <stop
                            offset="1"
                            style="
                                stop-color: rgb(140, 28, 66);
                                stop-opacity: 1;
                            "
                        />
                    </radialGradient>
                </defs>
            </svg>
        </div>
    </div>
</template>
