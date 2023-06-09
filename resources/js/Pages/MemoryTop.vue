<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";
import MemoryBack from "@/Components/MemoryBack.vue";

defineProps({});

const mounted = ref(false);

onMounted(() => (mounted.value = true));
</script>
<script>
import AppLayout from "../Layouts/AppLayout.vue";

export default {
    layout: AppLayout,
};
</script>
<style scoped>
.gradientGlass {
    background: linear-gradient(
        to right,
        rgba(45, 42, 45, 0.4) 5%,
        rgba(45, 42, 45, 0.2) 10%,
        rgba(45, 42, 45, 0.2) 90%,
        rgba(45, 42, 45, 0.4) 95%
    );
}
/* emittion yellow effect with shadow */
.emit {
    background-color: #ffec9b;
    box-shadow: 0 0 10px 1px #f6e05e;
    animation: emit 1s infinite;
}
</style>
<template>
    <div>
        <Head>
            <title>Memoories</title>
        </Head>
        <Teleport to='[data-slot="header"]' v-if="mounted">
            <p class="text-xs font-semibold text-gray-800">Memories</p>
        </Teleport>

        <div class="px-5">
            <div
                class="gradientGlass relative mx-auto mt-10 flex w-full md:w-fit flex-col justify-around gap-7 md:gap-20 rounded-md px-20 py-20 backdrop-blur-sm backdrop-saturate-[1.2] md:mt-20 md:px-32"
            >
                <Link
                    as="button"
                    :href="route('memory.search')"
                    class="rounded-lg bg-gradient-to-br from-ptr-dark-brown to-ptr-dark-brown/40 md:px-20 py-5 text-white"
                    >Memory Search</Link
                >
                <Link
                    as="button"
                    :href="route('memory.videos')"
                    class="rounded-lg bg-gradient-to-br from-ptr-dark-brown to-ptr-dark-brown/40 md:px-20 py-5 text-white"
                    >Video Search</Link
                >
                <Link
                    as="button"
                    :href="route('memory.collabo')"
                    class="rounded-lg bg-gradient-to-br from-ptr-dark-brown to-ptr-dark-brown/40 md:px-20 py-5 text-white"
                    >Collabo Search</Link
                >
                <div class="emit absolute left-0 h-full w-1 rounded-md"></div>
                <div class="emit absolute right-0 h-full w-1 rounded-md"></div>
            </div>
        </div>
        <MemoryBack
            class="fixed top-0 left-0 z-[-10] h-full w-full"
        ></MemoryBack>
    </div>
</template>
