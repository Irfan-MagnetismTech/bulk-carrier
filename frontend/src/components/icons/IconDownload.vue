<script setup>
import { useAttrs } from "@vue/runtime-core";

const attrs = useAttrs();
</script>

<template>
    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" :class="attrs.class" class="iconify iconify--grommet-icons w-5 h-5" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
        <path fill="none" stroke="currentColor" stroke-width="2" d="M2.998 1H17.5L21 4.5V23H3L2.998 1zM16 1v5h5m-9 3v9m-4-3l4 4l4-4" />
    </svg>
</template>