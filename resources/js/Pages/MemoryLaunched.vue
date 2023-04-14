<script setup>
import { ref } from "vue";
import { Link, Head } from "@inertiajs/vue3";
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
        <div class="mt-24 w-full text-center">
            <h2 class="text-3xl text-ptr-dark-pink">
                データの登録が完了しました。
            </h2>
            <p class="text-lg">ご協力、ありがとうございます。</p>
        </div>
        <div class="mx-auto mt-24 max-w-5xl text-center">
            <h2 class="mb-12 text-2xl text-ptr-main">
                データを続けて追加する場合はこちら
            </h2>
            <div class="flex w-full justify-evenly">
                <Link
                    :href="route('create.player', { id: props.videoId })"
                    as="button"
                    class="px-6 py-3 text-xl"
                    >同じ親動画内から</Link
                >
                <Link
                    :href="route('create.player')"
                    as="button"
                    class="px-6 py-3 text-xl"
                    >別の親動画から</Link
                >
            </div>
        </div>
    </div>
</template>
