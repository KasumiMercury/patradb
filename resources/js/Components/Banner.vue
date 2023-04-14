<script setup>
import { computed, ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";

const show = ref(true);
const message = computed(() => usePage().props.flash.message);

console.log(message.value);
// const message = "Hello World!"

watch(message, async () => {
    show.value = true;
});
</script>

<template>
    <div>
        <div
            v-if="show && message"
            class="fixed top-1/4 right-0 z-50 w-fit bg-ptr-dark-brown text-white"
        >
            <div class="mx-auto max-w-screen-xl py-2 px-3 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-center justify-between">
                    <div class="flex min-w-0 flex-1 items-center">
                        <p
                            class="ml-3 break-keep text-sm font-medium text-white"
                        >
                            {{ message }}
                        </p>
                    </div>

                    <div class="shrink-0 sm:ml-3">
                        <button
                            type="button"
                            class="-mr-1 flex rounded-md p-2 transition hover:bg-red-600 focus:bg-red-600 focus:outline-none sm:-mr-2"
                            aria-label="Dismiss"
                            @click.prevent="show = false"
                        >
                            <svg
                                class="h-5 w-5 text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
