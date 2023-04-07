<script setup>
defineProps({
    data: Object,
});
</script>

<template>
    <div class="border_gold border-b-12 bg-[#313231]">
        <div class="w-full select-none py-4 pl-8">
            <h1 class="text-xl text-white">In Progress</h1>
        </div>
        <div class="w-full px-4 pb-4">
            <p class="text-center" v-if="Object.keys(data).length === 0">
                None
            </p>
            <div v-else class="w-full py-4">
                <div
                    v-for="(data, index) in data"
                    :key="index"
                    class="text-center"
                >
                    <p>{{ data }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
