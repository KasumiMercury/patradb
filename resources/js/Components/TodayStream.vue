<script setup>
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
        <div class="bg-[#bf0060] px-6 pt-6 pb-3 lg:px-8 lg:pt-12 lg:pb-6">
            <div class="ml-0 mr-auto w-fit">
                <h1
                    class="w-fit rounded-3xl bg-[#fdfefd] py-4 px-8 text-xl shadow-inner shadow-gray-800"
                >
                    Today's Streaming
                </h1>
            </div>
            <div class="mt-8 bg-[#bf0060] pb-4">
                <div class="bg_gold rounded-3xl p-1.5">
                    <div class="rounded-3xl bg-[#A73946FF] py-6 px-6 lg:px-12">
                        <p
                            class="text-center text-2xl text-white"
                            v-if="Object.keys(data).length === 0"
                        >
                            今日の配信はお休みです
                        </p>
                        <template v-else>
                            <div v-for="(data, index) in data" :key="index">
                                <p class="w-fit rounded-3xl bg-white py-2 px-6">
                                    {{ getTime(data.start_date) }}
                                </p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg_gold z-30 h-1 w-full shadow shadow-white"></div>
    </div>
</template>
