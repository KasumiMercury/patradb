<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

const mounted = ref(false);

const props = defineProps({
    recentryChat: Object,
});

onMounted(() => {
    mounted.value = true;
});
</script>
<script>
import AppLayout from "../Layouts/AppLayout.vue";

export default {
    layout: (h, page) => h(AppLayout, { forceMenuHide: true }, () => page),
};
</script>
<style scoped>
.checker-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    overflow: hidden;
}
.checker {
    position: absolute;
    top: 0;
    left: 0;
    width: 150%;
    height: 150%;
    background-image: linear-gradient(
            45deg,
            #ff99cd 25%,
            transparent 25%,
            transparent 75%,
            #ff99cd 75%
        ),
        linear-gradient(
            45deg,
            #ff99cd 25%,
            transparent 25%,
            transparent 75%,
            #ff99cd 75%
        );
    background-repeat: repeat;
    background-size: 60px 60px;
    background-position: 0 0, 30px 30px;
    transform: translate(-20%, -50%);
    animation: slide 10s linear infinite;
}

@keyframes slide {
    0% {
        transform: translate(-20%, 0);
    }
    100% {
        transform: translate(calc(-20% - 30px), 30px);
    }
}
.number-vertical {
    text-combine-upright: all;
}
.wood-frame-date {
    /* wood frame gradient in vertical direction Three-dimensional effect */
    background: linear-gradient(
        90deg,
        #6a1206 0%,
        #d76035 0.7rem,
        #ffb576 0.65rem,
        #ffb576 calc(100% - 0.75rem),
        #d76035 calc(100% - 0.65rem),
        #6a1206 100%
    );
}
.wood-frame-message {
    background: linear-gradient(
        90deg,
        #6a1206 0.2rem,
        #d76035 0.8rem,
        #d76035 1.6rem,
        #ffb576 2rem,
        #ffb576 calc(100% - 2rem),
        #d76035 calc(100% - 1.6rem),
        #d76035 calc(100% - 0.8rem),
        #6a1206 calc(100% - 0.2rem)
    );
}
.wood-frame::after {
    /* wood frame gradient in horizontal direction Three-dimensional effect */
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        180deg,
        #6a1206 0%,
        #d76035 0.3rem,
        transparent 0.5rem,
        transparent calc(100% - 0.5rem),
        #d76035 calc(100% - 0.3rem),
        #6a1206 100%
    );
    z-index: 3;
    border-radius: 0.75rem;
}
.decorate-date {
    background: linear-gradient(
        90deg,
        #f7d69b 15%,
        #d76035 18%,
        #310000 20%,
        #310000 80%,
        #d76035 90%,
        #f7d69b 95%
    );
}
.decorate-date::before {
    position: absolute;
    content: "";
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        180deg,
        #f1d7a6 5%,
        #d76035 10%,
        transparent 20%,
        transparent 60%,
        #d76035 70%,
        #f1d7a6 80%,
        #9a2c16 90%,
        #560a09 100%
    );
}
.decorate-date::after {
    position: absolute;
    content: "";
    top: 10%;
    left: 0;
    width: 100%;
    height: 70%;
    background: linear-gradient(
        90deg,
        #f7d69b 15%,
        #d76035 18%,
        transparent 20%,
        transparent 80%,
        #d76035 90%,
        #f7d69b 95%
    );
}
.day-of-week {
    position: relative;
}
.day-of-week::before {
    content: "〔";
    position: absolute;
    top: 0;
}
.day-of-week::after {
    content: "〕";
    position: absolute;
    top: 0;
}
.chat-wrapper {
    filter: url("#pixelate");
}
.text-shadow {
    text-shadow: 3px 3px 2px #000;
}
</style>
<template>
    <div>
        <Head>
            <title>Chat</title>
        </Head>
        <svg>
            <filter id="pixelate" x="0" y="0">
                <feFlood x="0.3" y="0.3" height="0.1" width="0.1" />

                <feComposite width="0.8" height="0.8" />

                <feTile result="a" />

                <feComposite in="SourceGraphic" in2="a" operator="in" />

                <feMorphology operator="dilate" radius="0.5" />
            </filter>
        </svg>

        <div class="chat-wrapper absolute top-0 left-0 h-full w-full">
            <div class="flex h-full w-full flex-col">
                <div class="h-12 w-full grow-0"></div>
                <div
                    class="relative z-0 mx-auto aspect-video max-h-full max-w-full grow px-0 lg:px-6"
                >
                    <div
                        class="absolute top-1/2 right-12 h-fit w-fit -translate-y-2/3 rounded-xl border-4 border-[#3c0502] bg-[#841414] p-2"
                    >
                        <div
                            class="wood-frame-date wood-frame relative h-fit w-fit rounded-xl border-4 border-[#3c0502] p-3 text-white"
                        >
                            <div
                                class="relative h-fit rounded-xl border-2 border-[#3c0502] bg-gradient-to-r from-[#008100] to-[#004100] p-3 leading-10 tracking-widest shadow-inner shadow-custom-shadow after:absolute after:top-0 after:left-0 after:h-full after:w-full after:rounded-xl after:shadow-inner after:shadow-black"
                            >
                                <p
                                    class="text-shadow font-Zen text-3xl vertical-rl"
                                >
                                    <span class="number-vertical">23</span> 年
                                    <span class="number-vertical">4</span> 月
                                    <span class="number-vertical">8</span> 日
                                    <span
                                        class="number-vertical day-of-week before:left-0 before:-translate-x-2/3 after:right-0 after:translate-x-2/3"
                                        >日</span
                                    >
                                </p>
                            </div>
                        </div>
                        <div
                            class="absolute top-1/2 left-0 h-1/6 w-3 -translate-y-1/2"
                        >
                            <div
                                class="decorate-date relative h-full w-full"
                            ></div>
                        </div>
                        <div
                            class="absolute top-1/2 right-0 h-1/6 w-3 -translate-y-1/2"
                        >
                            <div
                                class="decorate-date relative h-full w-full"
                            ></div>
                        </div>
                    </div>
                    <div
                        class="wood-frame-message wood-frame absolute bottom-3 left-1/2 h-fit w-full max-w-4xl -translate-x-1/2 rounded-xl py-2 px-8 text-white"
                    >
                        <div
                            class="to-60% relative flex h-fit w-full flex-row rounded-xl bg-gradient-to-b from-[#008100] to-[#004100] py-6 leading-10 tracking-widest after:absolute after:top-0 after:left-0 after:h-full after:w-full after:rounded-xl after:shadow-inner after:shadow-black"
                        >
                            <div
                                class="text-shadow w-fit grow-0 px-12 font-Zen text-3xl font-light"
                            >
                                <p class="whitespace-nowrap">周防</p>
                            </div>
                            <div class="grow">
                                <p
                                    class="text-shadow h-32 max-w-full font-Zen text-3xl font-light"
                                >
                                    「{{ recentryChat[0]["message"] }}」
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Teleport to='[data-slot="bg-wrapper"]' v-if="mounted">
            <div class="checker-wrapper">
                <div class="checker"></div>
                <div class="checker"></div>
            </div>
        </Teleport>
    </div>
</template>
