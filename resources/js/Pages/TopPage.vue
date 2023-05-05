<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted, computed, watch } from "vue";
import TodayStream from "../Components/TodayStream.vue";
import RssStream from "../Components/RssStream.vue";
import TodaySchedule from "../Components/TodaySchedule.vue";
import MonthlySchedule from "../Components/MonthlySchedule.vue";
import balloonRainbow from "../Components/balloonRainbow.vue";
import Banner from "@/Components/Banner.vue";
import pkg from "lodash";
import firebase from "../plugins/firebase";
import { getMessaging, getToken, onMessage } from "firebase/messaging";
import axios from "axios";

const isNotificationPermissionDenied = ref(true);
const isNotificationPermissionError = ref(false);
const fcmToken = ref("");
const isTokenRegisteredUser = ref(false);
const permissionLoading = ref(false);
// const analytics = getAnalytics(firebase);
const messaging = getMessaging(firebase);

const checkToken = () => {
    if (fcmToken.value !== "") {
        isNotificationPermissionDenied.value = false;
        return;
    }
    getToken(messaging, {
        vapidKey:
            "BLkvwI8iBKXap-3P8T2iw2ETy4NybFkXHJWxm-SK_sSGFxlN0ntZTKvRzQx84HOFEJxBscjfGoPvSwegd8C6fSk",
    })
        .then((currentToken) => {
            if (currentToken) {
                fcmToken.value = currentToken;
                if (usePage().props.auth.user !== null) {
                    axios.post("/api/notification/user", {
                        userId: usePage().props.auth.user.id,
                        fcmToken: currentToken,
                    });
                } else {
                    axios
                        .get(
                            route("check.notification.user", {
                                fcmToken: currentToken,
                            })
                        )
                        .then((res) => {
                            console.log(res);
                            if (res.data.isExist) {
                                console.log("exist");
                                isTokenRegisteredUser.value = true;
                            }
                        });
                }
                // console.log(currentToken);
                permissionLoading.value = false;
                isNotificationPermissionDenied.value = false;
            } else {
                console.log(
                    "No registration token available. Request permission to generate one."
                );
                permissionLoading.value = true;
                requestPermission();
            }
        })
        .catch((err) => {
            console.log("An error occurred while retrieving token. ", err);
            isNotificationPermissionError.value = true;
            permissionLoading.value = false;
        });
};

const requestPermission = () => {
    console.log("Requesting Permission");
    Notification.requestPermission()
        .then((permission) => {
            if (permission === "granted") {
                isNotificationPermissionDenied.value = false;
                isNotificationPermissionError.value = false;
                console.log("Notification permission granted.");
            }
        })
        .catch((err) => {
            console.log("Error Occured");
        });
};
onMessage(messaging, (payload) => {
    console.log("Message received. ", payload);
});

const { debounce, chunk, sortedIndexBy, sortedIndex } = pkg;

const isServer = typeof window === "undefined";
const rssStream = ref();

const mounted = ref(false);
const svgWrapper = ref(null);
const time = ref(0);
const commandFire = ref(false);

const POINTS_COUNT = ref(4);
const MAX_Y = 100;
const WIDTH = ref(600);
const EASE = 0.4;
const SPEED = 0.001;
const WAVE_SCALE = (1 / Math.PI) * 1;
const SPEED2 = 0.0005;
const WAVE_SCALE2 = (1 / Math.PI) * 0.5;
const margin = 100;
const HEIGHT = ref(600);

const cokeTimer = ref();
const diffY = ref("");

const showScrollBaloon = ref(false);

const scrollTop = () => {
    window.scroll({
        top: 0,
        behavior: "smooth",
    });
};
const showScrollTop = () => {
    if (window.pageYOffset > 100) {
        showScrollBaloon.value = true;
    } else {
        showScrollBaloon.value = false;
    }
};

const command = [
    "ArrowUp",
    "ArrowUp",
    "ArrowDown",
    "ArrowDown",
    "ArrowLeft",
    "ArrowRight",
    "ArrowLeft",
    "ArrowRight",
    "KeyB",
    "KeyA",
];
let keyInputArray = [];
// konami command
const onKeyDown = (e) => {
    keyInputArray.push(e.code);
    if (keyInputArray.length >= 10) {
        keyInputArray = keyInputArray.slice(-10);
        if (String(keyInputArray) === String(command)) {
            console.log("command fire");
            cokeEffect();
        }
    }
};

const cokeEffect = () => {
    commandFire.value = true;
    generateBubbles();
    cokeTimer.value = setTimeout(() => {
        commandFire.value = false;
    }, 10000);
};

const changeWidth = () => {
    WIDTH.value = svgWrapper.value.clientWidth + margin;
    HEIGHT.value = svgWrapper.value.clientHeight;
    diffY.value = "-" + HEIGHT.value + "px";
    if (WIDTH.value > 800) {
        POINTS_COUNT.value = 6;
    } else {
        POINTS_COUNT.value = 2;
    }
};

const values = computed(() => {
    return new Array(POINTS_COUNT.value).fill(0).map((_, index) => {
        const x = index / POINTS_COUNT.value;
        const yBase = index % 2 === 0 ? 1 : -1;
        const y =
            Math.sin(x / WAVE_SCALE - time.value * SPEED) *
            (Math.sin(x / WAVE_SCALE2 - time.value * SPEED2) * 0.5) *
            yBase *
            Math.cos((1 / Math.PI) * 2);
        return y;
    });
});
const valuesToPathStr = (values) => {
    if (!values.length) {
        return "M0,0";
    }
    const points = values.map((y, x) => ({
        x: (x / (POINTS_COUNT.value - 1)) * WIDTH.value,
        y: y * MAX_Y + HEIGHT.value / 2,
    }));
    const firstPoint = points[0];
    const endPoint = { x: WIDTH.value + margin / 2, y: HEIGHT.value };
    const controlX = (WIDTH.value / (POINTS_COUNT.value - 1)) * EASE;
    return (
        `M${-margin},0 L${firstPoint.x - margin},${firstPoint.y} S` +
        points
            .map(
                (p) =>
                    `${p.x - controlX + margin},${p.y} ${p.x + margin},${p.y}`
            )
            .join(" ") +
        ` V${endPoint.x + margin},0`
    );
};
const pathStr = computed(() => valuesToPathStr(values.value));

onMounted(() => {
    document.addEventListener("keydown", onKeyDown);
    mounted.value = true;
    if (!isServer) {
        window.addEventListener("resize", changeWidth);
        window.addEventListener("scroll", showScrollTop, { passive: true });
    }
    const startTime = Date.now();
    const update = () => {
        time.value = Date.now() - startTime;
        requestAnimationFrame(update);
    };
    update();
});

watch(
    () => svgWrapper.value,
    () => {
        changeWidth();
    }
);

onUnmounted(() => {
    clearTimeout(cokeTimer.value);
    document.removeEventListener("keydown", onKeyDown);
    if (!isServer) {
        window.removeEventListener("resize", changeWidth);
        window.removeEventListener("scroll", showScrollTop, { passive: true });
    }
});

const bubbles = ref([]);

const generateBubbles = () => {
    for (let i = 0; i < 60; i++) {
        bubbles.value.push({
            left: Math.random() * 100,
            delay: Math.random() * 10,
            size: Math.floor(Math.random() * 3) * 10,
        });
    }
};

defineProps({
    todayStream: Object,
    tomorrowStream: Object,
    today: Object,
    coming: Object,
    persistent: Object,
    rss: Object,
    month: Object,
    other: Object,
});
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
.dobodobo-bg {
    background-color: #ffb6ba;
    background-image: radial-gradient(#f8abaf 20%, transparent 20%),
        radial-gradient(#f8abaf 20%, transparent 20%);
    background-size: 16px 16px;
    background-position: 0 0, 8px 8px;
}
.coke-bg {
    background-color: #9d5041;
}

.bubble {
    position: absolute;
    bottom: -30px;
    border-radius: 50%;
    background-image: radial-gradient(transparent 30%, #fff 100%);
    animation-name: float;
    animation-duration: 3s;
    animation-timing-function: ease-out;
    animation-iteration-count: infinite;
}
.bubble.small {
    width: 10px;
    height: 10px;
}

.bubble.medium {
    width: 20px;
    height: 20px;
}

.bubble.large {
    width: 30px;
    height: 30px;
}

@keyframes float {
    0% {
        transform: translateY(30px);
        opacity: 1;
    }
    50% {
        opacity: 1;
    }
    100% {
        transform: translateY(-800px);
        opacity: 0;
    }
}
</style>
<template>
    <div>
        <Head>
            <title>Schedule</title>
        </Head>
        <Teleport to='[data-slot="header"]' v-if="mounted">
            <p class="text-xs font-semibold text-gray-800">Schedule</p>
        </Teleport>
        <div class="px-7 pt-12 pb-40 xl:px-24">
            <Banner />
            <div class="ml-0 mr-auto w-full max-w-7xl lg:w-2/3">
                <TodaySchedule
                    :data="today"
                    :coming="coming"
                    :persistent="persistent"
                    :permissionLoading="permissionLoading"
                    :isNotificationPermissionDenied="
                        isNotificationPermissionDenied
                    "
                    :isNotificationPermissionError="
                        isNotificationPermissionError
                    "
                    :fcmToken="fcmToken"
                    :isTokenRegisteredUser="isTokenRegisteredUser"
                    @requestPermission="requestPermission"
                    @checkToken="checkToken"
                />
            </div>
            <div class="mr-0 ml-auto mt-12 w-full max-w-7xl lg:mt-24 lg:w-2/3">
                <MonthlySchedule
                    :monthData="month"
                    :otherData="other"
                    class="rounded-br-lg rounded-bl-3xl before:left-2 before:rounded-bl-3xl before:rounded-br-lg"
                />
            </div>
            <div
                class="ml-0 mr-0 mt-12 w-full max-w-7xl lg:mt-24 lg:mr-auto lg:w-2/3"
            >
                <TodayStream
                    :today="todayStream"
                    :tomorrow="tomorrowStream"
                    :persistent="persistent"
                    :permissionLoading="permissionLoading"
                    :isNotificationPermissionDenied="
                        isNotificationPermissionDenied
                    "
                    :isNotificationPermissionError="
                        isNotificationPermissionError
                    "
                    :fcmToken="fcmToken"
                    :isTokenRegisteredUser="isTokenRegisteredUser"
                    @requestPermission="requestPermission"
                    @checkToken="checkToken"
                />
            </div>
            <div class="mt-12 mr-0 ml-auto w-full max-w-7xl lg:mt-24 lg:w-2/3">
                <RssStream
                    :data="rss"
                    ref="rssStream"
                    :permissionLoading="permissionLoading"
                    :isNotificationPermissionDenied="
                        isNotificationPermissionDenied
                    "
                    :isNotificationPermissionError="
                        isNotificationPermissionError
                    "
                    :fcmToken="fcmToken"
                    :isTokenRegisteredUser="isTokenRegisteredUser"
                    @requestPermission="requestPermission"
                    @checkToken="checkToken"
                />
            </div>
        </div>
        <Teleport to='[data-slot="bg-wrapper"]' v-if="mounted">
            <div
                class="liquid-bg fixed top-0 left-0 z-[-10] h-full w-full overflow-hidden"
                ref="svgWrapper"
                :class="commandFire ? 'coke-bg' : 'dobodobo-bg'"
            >
                <div class="container" v-if="commandFire">
                    <div
                        class="bubble"
                        v-for="(bubble, index) in bubbles"
                        :key="index"
                        :style="{
                            left: bubble.left + '%',
                            animationDelay: bubble.delay + 's',
                            width: bubble.size + 'px',
                            height: bubble.size + 'px',
                        }"
                    ></div>
                </div>

                <div class="relative h-full w-full">
                    <div
                        class="absolute top-0 left-0 h-2/5 w-full bg-[#f7ea84]"
                    ></div>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        :width="WIDTH + margin"
                        :height="HEIGHT"
                        :viewBox="'0 0 ' + WIDTH + ' ' + HEIGHT"
                        class="absolute bottom-0 h-fit w-full"
                    >
                        <path
                            :d="pathStr"
                            stroke-width="6"
                            class="fill-[#f7ea84] stroke-[#fffbfb]"
                        />
                    </svg>
                </div>
            </div>
        </Teleport>
        <balloonRainbow
            :balloonShow="showScrollBaloon"
            @scrollTop="scrollTop"
            class="fixed bottom-5 right-0 mb-12 lg:right-5 lg:mb-0"
        ></balloonRainbow>
    </div>
</template>
