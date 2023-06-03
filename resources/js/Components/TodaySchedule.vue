<script setup>
import dayjs from "dayjs";
import chroma from "chroma-js";
import Modal from "./Modal.vue";
import { ref, watch } from "vue";
import { Link } from "@inertiajs/vue3";
import pkg from "lodash";
import axios from "axios";
import "vue3-carousel/dist/carousel.css";
import { Carousel, Slide, Pagination, Navigation } from "vue3-carousel";
const { debounce, chunk, sortedIndexBy, sortedIndex } = pkg;

let settings = {
    autoplay: 4000,
    snapAlign: "start",
    transition: 500,
};
let breakpoints = {
    768: {
        itemsToShow: 2,
        snapAlign: "start",
    },
};

const props = defineProps({
    data: Object,
    coming: Object,
    persistent: Object,
    isNotificationPermissionDenied: Boolean,
    isNotificationPermissionError: Boolean,
    permissionLoading: Boolean,
    fcmToken: String,
    isTokenRegisteredUser: Boolean,
});
const registeredTime = ref([]);
// time select value definition per hour (0-23) label and value
const timeDefinition = ref([]);
// generate time select value and label definition
for (let i = 0; i < 24; i++) {
    timeDefinition.value.push({
        label: i.toString().padStart(2, "0") + ":00",
        value: i.toString().padStart(2, "0"),
    });
}
timeDefinition.value = chunk(timeDefinition.value, 6);

const timeSelecting = ref(false);

const emits = defineEmits(["requestPermission", "checkToken"]);

const formatDay = (input) => {
    const day = dayjs(input);
    return day.format("MM/DD");
};
const formatTime = (input) => {
    const day = dayjs(input);
    return day.format("HH:mm");
};

const cols = chroma
    .scale(["#c20063", "#D8346E", "#ff99cd"])
    .classes(Object.keys(props.data).length)
    .mode("lch");

const NotificationModal = ref(false);
const openModal = () => {
    emits("checkToken");
    NotificationModal.value = true;
};
const registerNewTime = (time) => {
    axios
        .post(route("register.notification.schedule"), {
            fcmToken: props.fcmToken,
            time: time,
        })
        .then((res) => {
            if (res.data.registered) {
                registeredTime.value.push(time);
            }
        });
};
const removeTime = (time) => {
    axios
        .post(route("remove.notification.schedule"), {
            fcmToken: props.fcmToken,
            time: time,
        })
        .then((res) => {
            if (res.data.removed) {
                registeredTime.value.splice(
                    registeredTime.value.indexOf(time),
                    1
                );
            }
        });
};
const clearTime = () => {
    axios
        .post(route("clear.notification.schedule"), {
            fcmToken: props.fcmToken,
        })
        .then((res) => {
            if (res.data.cleared) {
                registeredTime.value = [];
            }
        });
};
const controllTime = (time) => {
    if (registeredTime.value.includes(time)) {
        removeTime(time);
    } else {
        registerNewTime(time);
    }
};
watch(
    () => props.fcmToken,
    () => {
        axios
            .post(route("get.notification.schedule"), {
                fcmToken: props.fcmToken,
            })
            .then((res) => {
                let schedules = res.data.schedules;
                registeredTime.value = schedules;
            });
    }
);
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
                class="flex w-full flex-col items-stretch justify-center 2xl:flex-row 2xl:flex-wrap"
            >
                <div
                    v-for="(data, index) in props.data"
                    :key="index"
                    class="my-2 mx-auto w-full px-2 lg:my-4 2xl:w-1/2"
                    :class="index % 2 === 0 ? 'ml-0 mr-auto' : 'mr-0 ml-auto'"
                >
                    <div
                        class="flex h-full w-full flex-col justify-start rounded-md shadow shadow-custom-shadow md:flex-row"
                    >
                        <div
                            class="mr-2 flex w-full flex-row items-baseline justify-center gap-2 rounded-t-md py-3 px-2 text-white md:w-24 md:flex-col md:items-center md:gap-0 md:rounded-l-md md:rounded-tr-none lg:mr-4"
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
                            <p class="text-md title-display lg:text-xl">
                                {{ data["event_title"] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Modal -->
        <div class="ml-auto mt-4 mr-2 w-fit">
            <button class="btn-primary btn text-white" @click="openModal">
                通知設定
            </button>
            <Modal :show="NotificationModal" @close="NotificationModal = false">
                <div>
                    <div v-if="isNotificationPermissionDenied">
                        <p class="my-2 text-center">
                            通知が許可されていません。
                        </p>
                        <button
                            v-if="!isNotificationPermissionError"
                            class="btn-secondary btn-block btn text-white"
                            @click="$emit('requestPermission')"
                        >
                            通知を許可
                        </button>
                        <p
                            v-if="isNotificationPermissionError"
                            class="text-center"
                        >
                            ブラウザの設定を変更してください。
                        </p>
                    </div>
                    <template v-if="!fcmToken">
                        <div class="divider"></div>
                        <div>
                            <p class="mt-5 text-center">
                                トークンを読込中です。
                            </p>
                            <progress
                                class="progress progress-secondary w-full"
                            ></progress>
                        </div>
                    </template>
                    <template v-else>
                        <template v-if="!$page.props.auth.user">
                            <template v-if="isTokenRegisteredUser">
                                <div
                                    class="flex flex-col items-center justify-center"
                                >
                                    <p class="text-sm md:text-base">
                                        トークンに紐付けられたアカウントを検知しました。
                                    </p>
                                    <p class="text-sm md:text-base">
                                        設定を変更するにはログインする必要があります。
                                    </p>
                                    <p class="my-4 text-center text-sm">
                                        だまして悪いが、仕様なんでな　<Link
                                            class="px-1n link-primary link"
                                            :href="route('transition.login')"
                                            >ログイン</Link
                                        >してもらおう
                                    </p>
                                </div>
                            </template>
                            <template v-else>
                                <div class="flex flex-col items-center">
                                    <h3
                                        class="break-words text-center text-sm md:text-base"
                                    >
                                        8:00（JST）に、当日締め切りの予定がある際、プッシュ通知で警告します。
                                    </h3>
                                    <template v-if="registeredTime.includes(8)">
                                        <button
                                            class="btn-primary btn-wide btn my-4 text-white"
                                            @click="controllTime(8)"
                                        >
                                            通知を有効化
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button
                                            class="btn-secondary btn-wide btn my-4 text-white"
                                            @click="controllTime(8)"
                                        >
                                            通知を有効化
                                        </button>
                                    </template>
                                </div>
                                <div class="divider"></div>
                                <div class="collapse-arrow collapse">
                                    <input
                                        type="checkbox"
                                        class="peer h-full w-full"
                                    />
                                    <div
                                        class="collapse-title text-center text-sm md:text-base"
                                    >
                                        Custom Time
                                    </div>
                                    <div class="collapse-content">
                                        <div class="relative">
                                            <div
                                                class="absolute z-10 h-full w-full rounded-md bg-ptr-dark-brown/80"
                                            >
                                                <p
                                                    class="absolute top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 text-xs text-ptr-light-pink break-keep md:text-base"
                                                >
                                                    ログインユーザーのみの機能です。
                                                </p>
                                            </div>
                                            <div class="mx-auto my-2 w-fit">
                                                <button
                                                    class="btn-wide btn flex items-center"
                                                    @click="
                                                        timeSelecting = true
                                                    "
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-6 w-6"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            fill="#000000"
                                                            d="M11 19v-6H5v-2h6V5h2v6h6v2h-6v6Z"
                                                        ></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <p class="my-3 text-center text-sm">
                                            だまして悪いが、仕様なんでな　<Link
                                                class="px-1n link-primary link"
                                                :href="
                                                    route('transition.login')
                                                "
                                                >ログイン</Link
                                            >してもらおう
                                        </p>
                                    </div>
                                </div>
                            </template>
                        </template>
                        <template v-else>
                            <h3
                                class="break-words text-center text-sm break-keep md:text-base"
                            >
                                指定時刻（JST）に、当日締め切りの予定がある際、プッシュ通知で警告します。
                            </h3>
                            <div
                                class="mt-2 mb-6 flex max-h-60 flex-col gap-6 overflow-y-scroll p-2"
                            >
                                <div
                                    class="grid grid-cols-4 gap-y-2 gap-x-2"
                                    v-for="(timeArray, index) in timeDefinition"
                                    :key="index"
                                >
                                    <template
                                        v-for="(time, index) in timeArray"
                                        :key="time.value"
                                    >
                                        <template
                                            v-if="
                                                registeredTime.includes(
                                                    time.value
                                                )
                                            "
                                        >
                                            <button
                                                class="btn-secondary btn-sm btn flex flex-row items-center justify-center md:gap-1"
                                                @click="
                                                    controllTime(time.value)
                                                "
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 384 512"
                                                    class="h-3 w-3"
                                                    fill="currentColor"
                                                >
                                                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                    <path
                                                        d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"
                                                    />
                                                </svg>
                                                <span
                                                    class="text-xs md:text-base"
                                                    >{{ time.label }}</span
                                                >
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button
                                                class="btn-sm btn flex flex-row items-center justify-center md:gap-1"
                                                @click="
                                                    controllTime(time.value)
                                                "
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 448 512"
                                                    class="h-3 w-3"
                                                    fill="currentColor"
                                                >
                                                    <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                    <path
                                                        d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"
                                                    />
                                                </svg>
                                                <span
                                                    class="text-xs md:text-base"
                                                    >{{ time.label }}</span
                                                >
                                            </button>
                                        </template>
                                    </template>
                                </div>
                            </div>
                            <button
                                :disabled="registeredTime.length === 0"
                                class="btn-secondary btn-block btn mx-auto"
                                @click="clearTime()"
                            >
                                通知を一括無効化
                            </button>
                        </template>
                    </template>
                </div>
            </Modal>
        </div>
        <h3 class="whitespace-nowrap px-6 text-lg md:px-12">Comming</h3>
        <div class="w-full pb-4 lg:px-4">
            <div
                class="items flex w-full flex-col items-stretch justify-center 2xl:flex-row 2xl:flex-wrap"
            >
                <div
                    v-for="(data, index) in props.coming"
                    :key="index"
                    class="my-2 mx-auto w-full px-2 lg:my-4 2xl:w-1/2"
                    :class="index % 2 === 0 ? 'ml-0 mr-auto' : 'mr-0 ml-auto'"
                >
                    <div
                        class="flex h-full w-full flex-col justify-start rounded-md shadow shadow-custom-shadow md:flex-row"
                    >
                        <div
                            class="mr-2 flex w-full flex-row items-end justify-center gap-2 rounded-t-md bg-ptr-dark-brown py-3 px-2 text-white md:w-24 md:flex-col md:items-center md:gap-0 md:rounded-l-md md:rounded-tr-none lg:mr-4"
                        >
                            <div
                                class="flex w-fit flex-col items-baseline md:w-full"
                            >
                                <p class="whitespace-nowrap text-base">
                                    {{ formatDay(data["start_date"]) }}
                                </p>
                                <p class="ml-1 whitespace-nowrap text-sm">
                                    {{ formatTime(data["start_date"]) }}
                                </p>
                            </div>
                            <div
                                class="flex w-fit flex-row items-center justify-end gap-2 md:w-full md:gap-1"
                            >
                                <span>→</span>
                                <div class="flex flex-col items-end">
                                    <p class="whitespace-nowrap text-sm">
                                        {{ formatDay(data["end_date"]) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="py-2 px-4">
                            <p class="text-md title-display lg:text-xl">
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
                        class="flex h-full w-full flex-col justify-start rounded-md shadow shadow-custom-shadow md:flex-row"
                    >
                        <div
                            class="mr-2 flex w-full flex-row items-end justify-center gap-2 rounded-t-md bg-ptr-dark-brown py-3 px-2 text-white md:w-24 md:flex-col md:items-center md:gap-0 md:rounded-l-md md:rounded-tr-none lg:mr-4"
                        >
                            <div
                                class="flex w-fit flex-col items-baseline md:w-full"
                            >
                                <p class="whitespace-nowrap text-base">
                                    {{ formatDay(data["start_date"]) }}
                                </p>
                                <p class="ml-1 whitespace-nowrap text-sm">
                                    {{ formatTime(data["start_date"]) }}
                                </p>
                            </div>
                            <div
                                class="flex w-fit flex-row items-center justify-end gap-2 md:w-full md:gap-1"
                            >
                                <span>→</span>
                                <span class="text-sm">None</span>
                            </div>
                        </div>
                        <div class="py-2 px-4">
                            <p class="text-md title-display lg:text-xl">
                                {{ data["event_title"] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ptr-horn -->
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
                    <g id="layer">
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
