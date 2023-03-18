<script setup>
import * as PIXI from "pixi.js";
defineProps({
    data: Object,
});
const getTime = (input) => {
    let inputArray = input.split(" ");
    return inputArray[1].substr(0, 5);
};
</script>
<template>
    <div>
        <div class="bg-[#bf0060] pt-6 lg:pt-12 pb-3 lg:pb-6 px-6 lg:px-8">
            <div class="w-fit ml-0 mr-auto">
                <h1
                    class="shadow-inner shadow-gray-800 text-xl bg-[#fdfefd] w-fit rounded-3xl py-4 px-8"
                >
                    Today's Streaming
                </h1>
            </div>
            <div class="bg-[#bf0060] pb-4 mt-8">
                <div class="rounded-3xl bg_gold p-1.5">
                    <div class="rounded-3xl bg-[#A73946FF] py-6 px-6 lg:px-12">
                        <p
                            class="text-center text-2xl text-white"
                            v-if="Object.keys(data).length === 0"
                        >
                            今日の配信はお休みです
                        </p>
                        <template v-else>
                            <div v-for="(data, index) in data" :key="index">
                                <p class="bg-white rounded-3xl py-2 px-6 w-fit">
                                    {{ getTime(data.start_date) }}
                                </p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full h-1 bg_gold shadow shadow-white z-30"></div>
    </div>
</template>
