<script setup>
import { watch } from "vue";

const props = defineProps({
    balloonShow: Boolean,
});
const emits = defineEmits(["scrollTop"]);
let randomNum = Math.floor(Math.random() * 7);
const cols = [
    "#ff8777",
    "#ffc983",
    "#ffe94a",
    "#c7eeba",
    "#a8d5fb",
    "#eec3f3",
    "#ffc9e3",
];
let balloonCol = cols[randomNum];

watch(
    () => props.balloonShow,
    () => {
        if (props.balloonShow) {
            //find diffrent color
            let newRandomNum = Math.floor(Math.random() * 7);
            while (newRandomNum === randomNum) {
                newRandomNum = Math.floor(Math.random() * 7);
            }
            randomNum = newRandomNum;
            balloonCol = cols[randomNum];
        }
    }
);
</script>
<style scoped>
.balloon-shadow {
    filter: drop-shadow(3px 3px 1px #fffafb);
}
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease, transform 0.5s ease;
    transform: translateX(0);
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
    transform: translateY(1rem);
}
</style>
<template>
    <div>
        <transition>
            <div v-if="balloonShow" class="relative">
                <div
                    class="pointer-events-none absolute bottom-1/2 left-1/2 z-10 h-fit w-fit -translate-y-1/2 -translate-x-1/2"
                >
                    <svg
                        class="relative h-6 w-6 animate-bounce fill-ptr-main"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 384 512"
                    >
                        <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <path
                        stroke-width="20"
                        class=" stroke-ptr-white"
                            d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2V448c0 17.7 14.3 32 32 32s32-14.3 32-32V141.2L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"
                        />
                    </svg>
                </div>

                <svg
                    class="balloon-shadow h-36 w-36 animate-float"
                    viewBox="0 0 2250 2250"
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
                    <path
                        d="M1107.69,1251.52c0,-0 44.865,295.624 44.403,445.526c-0.247,80.169 -52.647,212.504 -64.105,325.836c-12.13,119.968 16.651,218.826 16.651,218.826"
                        stroke-width="20"
                        class="cursor-pointer fill-none"
                        @click="emits('scrollTop')"
                        :style="'stroke:' + balloonCol"
                    />
                    <path
                        d="M1082.12,1225.82c-119.457,-46.992 -402.031,-315.361 -408.22,-733.243c-3.388,-228.728 126.674,-484.347 440.55,-476.786c346.422,8.346 479.57,317.683 436.373,616.789c-27.686,191.701 -206.33,504.919 -396.323,594.247l53.4,70.564c-0.569,5.45 -5.079,10.892 -11.792,12.485c-18.333,4.351 -26.473,16.571 -37.794,25.658c-19.495,-0.886 -74.676,-14.149 -92.788,-18.574c-12.374,-3.024 -9.318,-6.538 -9.318,-6.538l-16.137,-1.588c20.12,-31.741 41.522,-64.336 42.049,-83.014Z"
                        stroke-width="15"
                        class="cursor-pointer stroke-white"
                        @click="emits('scrollTop')"
                        :style="'fill:' + balloonCol"
                    />
                </svg>
            </div>
        </transition>
    </div>
</template>
