<script setup>
import PaginateButton from "./PaginateButton.vue";
import { ref, onMounted, watch } from "vue";
const props = defineProps({
    data: Object,
});

const total = ref(1);
const current = ref(1);
const items = ref([]);

onMounted(() => {
    setItems(props.data);
});

watch(props.data, (val) => {
    setItems(val);
});
const setItems = (data) => {
    total.value = data.last_page;
    current.value = data.current_page;

    if (total.value <= 9) {
        for (let i = 1; i <= total.value; i++) {
            items.value.push({
                index: i,
                display: i,
                active: i == current.value,
            });
        }
    } else {
        if (current.value <= 5) {
            for (let i = 1; i <= 6; i++) {
                items.value.push({
                    index: i,
                    display: i,
                    active: i == current.value,
                });
            }
            items.value.push({ index: 7, display: "...", active: false });
            for (let i = total.value - 1; i <= total.value; i++) {
                items.value.push({
                    index: i,
                    display: i,
                    active: i == current.value,
                });
            }
        } else if (current.value >= total.value - 4) {
            for (let i = 1; i <= 2; i++) {
                items.value.push({
                    index: i,
                    display: i,
                    active: i == current.value,
                });
            }
            items.value.push({
                index: current.value - 2,
                display: "...",
                active: false,
            });
            for (let i = total.value - 5; i <= total.value; i++) {
                items.value.push({
                    index: i,
                    display: i,
                    active: i == current.value,
                });
            }
        } else {
            for (let i = 1; i <= 2; i++) {
                items.value.push({
                    index: i,
                    display: i,
                    active: i == current.value,
                });
            }
            items.value.push({
                index: current.value - 2,
                display: "...",
                active: false,
            });
            for (let i = current.value - 1; i <= current.value + 1; i++) {
                items.value.push({
                    index: i,
                    display: i,
                    active: i == current.value,
                });
            }
            items.value.push({
                index: current.value + 2,
                display: "...",
                active: false,
            });
            for (let i = total.value - 1; i <= total.value; i++) {
                items.value.push({
                    index: i,
                    display: i,
                    active: i == current.value,
                });
            }
        }
    }
};
const emits = defineEmits(["paginate"]);
const select = (index) => {
    emits("paginate", index);
};
</script>
<template>
    <div class="flex flex-row justify-around gap-1">
        <PaginateButton @click="select(current - 1)" :hidden="current === 1">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 256 512"
                class="h-full w-full p-1"
            >
                <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                <path
                    d="M9.4 278.6c-12.5-12.5-12.5-32.8 0-45.3l128-128c9.2-9.2 22.9-11.9 34.9-6.9s19.8 16.6 19.8 29.6l0 256c0 12.9-7.8 24.6-19.8 29.6s-25.7 2.2-34.9-6.9l-128-128z"
                />
            </svg>
        </PaginateButton>
        <PaginateButton
            v-for="(link, index) in items"
            :key="index"
            @click="select(link.index)"
            :active="link.active"
            >{{ String(link.display) }}</PaginateButton
        >
        <PaginateButton
            @click="select(current + 1)"
            :hidden="current === total"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 256 512"
                class="h-full w-full p-1"
            >
                <!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                <path
                    d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z"
                />
            </svg>
        </PaginateButton>
    </div>
</template>
