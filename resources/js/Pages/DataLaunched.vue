<script setup>
import { ref } from "vue";
import { Link,Head } from "@inertiajs/vue3";
const props = defineProps({
    videoId: String,
});
console.log(props.videoId);
</script>
<script>
import AppLayout from "../Layouts/AppLayout.vue";

export default {
    layout: AppLayout,
};
</script>
<template>
    <div>
        <Head>
            <title>Successful Launch</title>
        </Head>
        <div class="w-full text-center mt-24">
            <h2 class="text-3xl text-ptr-dark-pink">データの登録が完了しました。</h2>
            <p class="text-lg">ご協力、ありがとうございます。</p>
        </div>
        <div class="max-w-5xl mt-24 mx-auto text-center">
        <h2 class="text-2xl text-ptr-main mb-12">データを続けて追加する場合はこちら</h2>
        <div class="w-full justify-evenly flex">
            <Link :href="route('create.player', { id: props.videoId })" as="button" class="text-xl px-6 py-3">同じ親動画内から</Link>
            <Link :href="route('create.player')" as="button" class="text-xl px-6 py-3">別の親動画から</Link>
        </div>
        </div>
    </div>
</template>
