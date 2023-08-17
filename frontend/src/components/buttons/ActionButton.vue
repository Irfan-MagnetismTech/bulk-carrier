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
    <router-link :to="to" class="relative" @mouseenter="isTooltipShowing = true" @mouseleave="isTooltipShowing = false">
      <div v-if="action == 'edit'" class="tooltip">
        <svg xmlns="http://www.w3.org/2000/svg" :class="classes ?? 'icn text-purple-500 dark:text-gray-400 dark:hover:text-purple-500'" viewBox="0 0 20 20" fill="currentColor">
          <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
          <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
        </svg>
        <span class="tooltiptext">{{ action }}</span>
      </div>
      <div v-else-if="action == 'show'" class="tooltip">
        <svg xmlns="http://www.w3.org/2000/svg" :class="classes ?? 'icn text-green-600 dark:text-gray-600 dark:hover:text-green-600'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          <span class="tooltiptext">{{ action }}</span>
        </svg>
        <span class="tooltiptext">{{ action }}</span>
      </div>
      <div v-else-if="action == 'delete'" class="tooltip">
        <svg xmlns="http://www.w3.org/2000/svg" :class="classes ?? 'icn text-red-500 dark:text-gray-400 dark:hover:text-red-500'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          <span class="tooltiptext">{{ action }}</span>
        </svg>
        <span class="tooltiptext">{{ action }}</span>
      </div>
      <div v-else-if="action == 'notify customer'" class="tooltip">
        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="32" height="32" :class="classes ?? 'icn iconify iconify--ic text-blue-500 dark:text-gray-400 dark:hover:text-blue-500'" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
          <path fill="currentColor" d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6zm-2 0l-8 5l-8-5h16zm0 12H4V8l8 5l8-5v10z"></path>
          <span class="tooltiptext">{{ action }}</span>
        </svg>
        <span class="tooltiptext">{{ action }}</span>
      </div>
      <!-- <div v-else-if="action == 'notify customer'" class="tooltip">
        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" class="icn iconify iconify--ic" width="32" height="32" :class="classes ?? 'text-blue-500 dark:text-gray-400 dark:hover:text-blue-500'" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
          <path fill="currentColor" d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6zm-2 0l-8 5l-8-5h16zm0 12H4V8l8 5l8-5v10z"></path>
          <span class="tooltiptext">{{ action }}</span>
        </svg>
        <span class="tooltiptext">{{ action }}</span>
      </div> -->
      <div v-else-if="action == 'copy contract'" class="tooltip">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
        </svg>
        <span class="tooltiptext">{{ action }}</span>
      </div>
      <div v-else-if="action == 'clear data'" class="tooltip">
        <svg class="w-6 h-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.999 511.999" style="enable-background:new 0 0 511.999 511.999" xml:space="preserve"><path style="fill:#ffdc64" d="m131.221 197.202-32.488 8.735 74.045 296.978h116.358z"/><path style="fill:#ffc850" d="m98.733 205.937 74.045 296.978h50.684L115.876 201.16z"/><path style="fill:#ffdc64" d="M250.078 25.103 104.704 170.477l30.857 25.655L255.21 56.206z"/><path style="fill:#7f8499" d="m395.452 119.169 28.945 23.328-18.14-54.421-42.327-6.047-48.375 48.374 6.047 42.328 54.421 18.14-23.328-28.945 5.188-37.57z"/><circle style="fill:#e1a546" cx="113.252" cy="187.585" r="25.654"/><path style="fill:#ffc850" d="m314.518 56.804-24.187 24.188a8.553 8.553 0 0 1-12.094 0L249.04 51.795c-10.019-10.019-10.019-26.262 0-36.28 10.019-10.019 26.262-10.019 36.28 0l29.197 29.197a8.548 8.548 0 0 1 .001 12.092z"/><circle style="fill:#959cb5" cx="339.737" cy="106.219" r="17.103"/><path d="m432.003 139.962-18.14-54.422a8.014 8.014 0 0 0-6.472-5.401l-42.327-6.047a8.02 8.02 0 0 0-6.803 2.267l-7.35 7.35a25.012 25.012 0 0 0-11.168-2.614 25 25 0 0 0-11.169 2.614l-14.811-14.811 6.424-6.424c6.46-6.46 6.46-16.971 0-23.431L290.99 9.846c-13.129-13.129-34.49-13.129-47.619 0-5.667 5.667-8.883 12.871-9.657 20.283l-123.96 123.962c-16.926 1.758-30.169 16.106-30.169 33.489 0 10.667 4.992 20.185 12.756 26.359l70.506 282.027h-15.385a8.017 8.017 0 0 0 0 16.034h162.477a8.017 8.017 0 0 0 0-16.034h-12.323L141.125 206.458a33.568 33.568 0 0 0 4.56-9.83L254.86 68.952l17.709 17.709a16.515 16.515 0 0 0 11.715 4.845c4.243 0 8.485-1.615 11.715-4.845l6.424-6.424 14.831 14.831a25.21 25.21 0 0 0 0 22.299l-7.368 7.369a8.021 8.021 0 0 0-2.268 6.803l6.047 42.327a8.02 8.02 0 0 0 5.401 6.472l54.421 18.14c.836.279 1.69.413 2.534.413a8.016 8.016 0 0 0 5.67-13.686l-21.353-21.353 4.63-32.407 32.407-4.63 21.353 21.353a8.017 8.017 0 0 0 13.275-8.206zm-318.757 29.981H113.267c9.721.005 17.629 7.915 17.629 17.637 0 9.726-7.912 17.638-17.638 17.638S95.62 197.306 95.62 187.58c-.002-9.722 7.905-17.631 17.626-17.637zm66.128 326.023-68.705-274.822c.855.065 1.717.109 2.588.109a33.451 33.451 0 0 0 15.568-3.83L279.39 495.966H179.374zm-35.357-322.059a33.876 33.876 0 0 0-15.22-16.185L237.315 49.204a33.583 33.583 0 0 0 6.057 8.26l.115.115-99.47 116.328zm140.645-98.583a.536.536 0 0 1-.756 0L254.71 46.127c-6.876-6.877-6.876-18.066 0-24.943a17.58 17.58 0 0 1 12.471-5.158c4.516 0 9.033 1.72 12.471 5.158l29.196 29.197a.536.536 0 0 1 0 .756l-24.186 24.187zm61.505 24.467a9.027 9.027 0 0 1 2.662 6.424 9.022 9.022 0 0 1-2.662 6.424c-1.716 1.716-3.998 2.662-6.424 2.662s-4.709-.945-6.424-2.661c-3.542-3.542-3.542-9.307 0-12.85a9.028 9.028 0 0 1 6.425-2.662 9.035 9.035 0 0 1 6.423 2.663zm-17.329 66.901-4.779-33.454 4.515-4.515a25.013 25.013 0 0 0 11.169 2.614c3.237 0 6.378-.614 9.298-1.777l-5.142 35.991a8.018 8.018 0 0 0 2.268 6.803l.173.173-17.502-5.835zm77.041-54.051a8.016 8.016 0 0 0-6.803-2.267l-35.991 5.142a25.08 25.08 0 0 0 1.777-9.299c0-3.941-.908-7.74-2.614-11.169l4.515-4.515 33.453 4.779 5.834 17.503-.171-.174z"/></svg>

        <span class="tooltiptext">{{ action }}</span>
      </div>
      <div v-else-if="action == 'download receipt'" class="tooltip">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        <span class="tooltiptext">{{ action }}</span>
      </div>
      <div v-else-if="action == 'activity log'" class="tooltip">
        <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.3rem; width: 1.3rem" class="" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="tooltiptext">{{ action }}</span>
      </div>
      <div v-else-if="action == 'insert'" class="tooltip">
        <svg xmlns="http://www.w3.org/2000/svg" class="icn" style="height: 1.3rem; width: 1.3rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="tooltiptext">{{ action }}</span>
      </div>
      <div v-else-if="action == 'download excel'" class="tooltip">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        <span class="tooltiptext">{{ action }}</span>
      </div>
      <div v-else-if="action == 'print'" class="tooltip">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
        </svg>
        <span class="tooltiptext">{{ action }}</span>
      </div>
      <div v-else-if="action == 'bulk-print'" class="tooltip">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
        </svg>
        <span class="tooltiptext">{{ action }}</span>
      </div>
    </router-link>
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