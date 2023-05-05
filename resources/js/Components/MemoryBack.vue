<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";

onMounted(() => {});
</script>
<style scoped>
.gradient-bg {
    /* background gradient top to bottom */
    background: linear-gradient(
        180deg,
        #d65988 5%,
        #fbb096 50%,
        #f6dce1 51%,
        #f8c3db 52%,
        #ee9cd0 60%,
        #f081ac 75%,
        #ee9cd0 85%,
        #ffdef2 100%
    );
}
</style>
<template>
    <div ref="canvasRef" class="gradient-bg"></div>
</template>
