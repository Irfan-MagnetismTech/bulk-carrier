<script setup>
import { ref } from 'vue';

const props = defineProps({
    to: {
        default: {},
        required: false,
    },
    classes: null,
    action: {
        default: null,
        required: true
    },
});
const isTooltipShowing = ref(false);
</script>
<template>
<!--    <div v-if="action == 'edit'" class="tooltip">-->
<!--      <svg xmlns="http://www.w3.org/2000/svg" class="icn" :class="classes ?? 'text-purple-500  dark:hover:text-purple-500'" viewBox="0 0 20 20" fill="currentColor">-->
<!--        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />-->
<!--        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />-->
<!--      </svg>-->
<!--      <span class="tooltiptext">{{ action }}</span>-->
<!--    </div>-->
</template>

<style lang="postcss" scoped>
.tooltip {
  position: relative;
  display: inline-block;
}

.tooltip .tooltiptext {
  text-transform: capitalize;
  visibility: hidden;
  width: 120px;
  background-color: black;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;
  position: absolute;
  z-index: 1;
  top: -140%;
  left: 50%;
  margin-left: -60px;
}

.tooltip .tooltiptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: black transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
}

/*.tooltip {*/
/*    @apply absolute -top-8 z-50 right-0 px-2 py-1 text-gray-200 bg-gray-700 rounded dark:bg-gray-600;*/
/*}*/
/*.mytooltip {*/
/*  @apply text-gray-200 bg-gray-700 dark:bg-gray-600;*/
/*}*/
.icn {
    @apply w-5 h-5 transition duration-150 ease-in-out hover:translate-y-1 hover:transform;
}
</style>