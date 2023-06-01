<script setup>
import { Head, Link, usePage, useForm } from "@inertiajs/vue3";
import { ref, onMounted, watch } from "vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const form = useForm({
    content: "",
    handleName: "",
    userId: usePage().props.auth.user.id,
});

form.handleName = usePage().props.auth.user.name;

const mounted = ref(false);
const remainTextLength = ref(1000);

onMounted(() => (mounted.value = true));

const submit = () => {
    form.transform((data) => ({
        ...data,
        handleName: data.handleName ? data.handleName : "匿名",
    })).post(route("post.schedule"));
};
watch(
    () => form.content,
    (value) => {
        remainTextLength.value = 1000 - value.length;
    }
);
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
            <title>CreateSchedule</title>
        </Head>
        <Teleport to='[data-slot="header"]' v-if="mounted">
            <p class="text-xs font-semibold text-gray-800">CreateSchedule</p>
        </Teleport>
        <div>
            <div
                class="mx-auto mb-12 flex w-fit flex-col px-4 pt-4 sm:px-6 lg:px-8"
            >
                <form @submit.prevent="submit">
                    <div class="mt-12 mb-3 text-xs break-keep md:text-sm">
                        <p>
                            スケジュールの内容がわかる単語・フレーズ・URL等を入力してください。
                        </p>
                        <p>
                            入力された内容をもとに管理犬が正確な情報をチェックし、システムに登録します。
                        </p>
                        <p>
                            管理犬が見て対象が特定できるものであれば何でも構いません。
                        </p>
                    </div>
                    <InputLabel for="notes" value="Notes" />
                    <textarea
                        v-model="form.content"
                        name="notes"
                        placeholder="グッズ締め切り 28日"
                        id="content"
                        cols="100"
                        rows="5"
                        class="textarea-bordered textarea textarea-sm w-full max-w-7xl"
                        required
                    ></textarea>
                    <div
                        class="mt-3 text-right text-xs text-primary"
                        v-if="remainTextLength < 100"
                    >
                        残り<span v-text="remainTextLength"></span>文字
                    </div>

                    <div class="mt-12 mb-3 text-sm">
                        <p>登録者として表示されるハンドルネームです。</p>
                    </div>
                    <InputLabel for="handleName" value="Handle Name" />
                    <TextInput
                        id="handleName"
                        v-model="form.handleName"
                        type="text"
                        class="mt-1 block w-full"
                        autofocus
                    />
                    <div class="mt-8 flex items-center justify-end">
                        <PrimaryButton
                            class="ml-4"
                            :class="{
                                'opacity-25':
                                    form.content === '' ||
                                    form.processing ||
                                    remainTextLength < 0,
                            }"
                            :disabled="form.processing"
                        >
                            Send
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
