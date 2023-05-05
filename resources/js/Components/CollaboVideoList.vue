<script setup>
const props = defineProps({
    item: Object,
});
const ids = props.item.map((obj) => obj.id);
</script>
<template>
    <div>
        {{ ids }}
    </div>
</template>
