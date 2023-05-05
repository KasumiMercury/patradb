<script setup>
import { ref, onMounted } from "vue";
import RssCard from "./RssCard.vue";
import "vue3-carousel/dist/carousel.css";
import { Carousel, Slide, Pagination, Navigation } from "vue3-carousel";
import { Link, usePage } from "@inertiajs/vue3";
import Modal from "./Modal.vue";
const props = defineProps({
    data: Object,
    isNotificationPermissionDenied: Boolean,
    isNotificationPermissionError: Boolean,
    permissionLoading: Boolean,
    fcmToken: String,
    isTokenRegisteredUser: Boolean,
});
let settings = {
    autoplay: 4000,
    snapAlign: "center",
    transition: 500,
};
let breakpoints = {
    640: {
        itemsToShow: 1.5,
        snapAlign: "center",
    },
    1024: {
        itemsToShow: 2.5,
        snapAlign: "center",
    },
};
if (Object.keys(props.data).length < 2) {
    settings = {
        snapAlign: "center",
    };
}
const emits = defineEmits(["requestPermission", "checkToken"]);

const NotificationModal = ref(false);
const openModal = () => {
    if (usePage().props.auth.user) {
        emits("checkToken");
    }
    NotificationModal.value = true;
};
</script>
<style deep>
:root {
    --vc-clr-primary: #d8346e;
    --vc-clr-secondary: #ff99cd;
    --vc-clr-white: #fffafb;
    --vc-clr-dark: #2d2a2d;

    /* nav */
    --vc-nav-width: 30px;
    --vc-nav-height: 30px;
    --vc-nav-border-radius: 9999px;
    --vc-nav-color: var(--vc-clr-primary);
    --vc-nav-color-hover: var(--vc-clr-secondary);
    --vc-nav-background: var(--vc-clr-white);

    /* Pagination */
    --vc-pgn-width: 8px;
    --vc-pgn-height: 8px;
    --vc-pgn-border-radius: 9999px;
    --vc-pgn-margin: 4px;
    --vc-pgn-background-color: var(--vc-clr-secondary);
    --vc-pgn-active-color: var(--vc-clr-primary);
}
.carousel__slide--prev,
.carousel__slide--next {
    opacity: 0.7;
    transform: scale(0.8);
}
.carousel__slide--sliding {
    transition: 0.5s;
}
.carousel__next,
.carousel__prev {
    border: 1px solid var(--vc-clr-primary);
}
</style>
<template>
    <div
        class="relative w-full rounded-t-lg rounded-br-lg rounded-bl-3xl bg-ptr-white px-4 pb-4 before:absolute before:top-2 before:left-2 before:-z-10 before:h-full before:w-full before:rounded-t-lg before:rounded-br-lg before:rounded-bl-3xl before:bg-ptr-dark-brown"
    >
        <div
            class="text-ptr-dark-brown flex w-full flex-row items-center pt-6 pb-3 md:pt-12 md:pb-6"
        >
            <h1 class="select-none flex items-center px-6 md:px-12 text-2xl">
                <svg
                    fill="none"
                    stroke-width="1.5"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true"
                    class="mr-2 h-6 w-6 stroke-ptr-dark-brown"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9.348 14.651a3.75 3.75 0 010-5.303m5.304 0a3.75 3.75 0 010 5.303m-7.425 2.122a6.75 6.75 0 010-9.546m9.546 0a6.75 6.75 0 010 9.546M5.106 18.894c-3.808-3.808-3.808-9.98 0-13.789m13.788 0c3.808 3.808 3.808 9.981 0 13.79M12 12h.008v.007H12V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"
                    ></path>
                </svg>
                Upcoming Stream
            </h1>
            <div class="ml-auto mr-2 w-fit">
                <button class="btn-primary btn text-white" @click="openModal">
                    通知設定
                </button>
                <Modal
                    :show="NotificationModal"
                    @close="NotificationModal = false"
                >
                    <div>
                        <template v-if="!$page.props.auth.user">
                            <h3 class="my-2 text-center text-lg">
                                ログインユーザーのみの機能です。
                            </h3>
                            <p class="my-3 text-center text-sm">
                                だまして悪いが、仕様なんでな　<Link
                                    class="px-1n link-primary link"
                                    :href="route('transition.login')"
                                    >ログイン</Link
                                >してもらおう
                            </p>
                        </template>
                        <template v-else>
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
                                <div>
                                    <h3
                                        class="break-words break-keep text-sm md:text-base"
                                    >
                                        配信枠のスケージュール時刻が変更を検知した際、警告通知を発信します。（予定変更警告・当日以外）
                                    </h3>
                                    <div class="mx-auto mt-1 mb-6 w-fit">
                                        <button class="btn-wide btn">
                                            通知を有効化
                                        </button>
                                    </div>
                                    <h3
                                        class="break-words break-keep text-sm md:text-base"
                                    >
                                        週間予定と異なる時刻を設定された配信枠を検知した際、警告通知を発信します。（予定変更疑惑警告・当日以外）
                                    </h3>
                                    <div class="mx-auto mt-1 mb-6 w-fit">
                                        <button class="btn-wide btn">
                                            通知を有効化
                                        </button>
                                    </div>
                                    <h3
                                        class="break-words break-keep text-sm md:text-base"
                                    >
                                        配信枠が立ったことを検知した際、通知を発信します。（配信枠通知・当日以外）
                                    </h3>
                                    <div class="mx-auto mt-1 mb-6 w-fit">
                                        <button class="btn-wide btn">
                                            通知を有効化
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </template>
                    </div>
                </Modal>
            </div>
        </div>
        <div class="relative">
            <Carousel
                v-bind="settings"
                :breakpoints="breakpoints"
                :wrap-around="Object.keys(props.data).length > 1"
            >
                <Slide v-for="(item, index) in props.data" :key="index">
                    <RssCard :item="item" class="h-full"></RssCard>
                </Slide>

                <template #addons>
                    <Navigation />
                    <Pagination />
                </template>
            </Carousel>
        </div>
    </div>
</template>
