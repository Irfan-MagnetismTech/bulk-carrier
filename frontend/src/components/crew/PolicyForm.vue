<script setup>
import Error from "../Error.vue";
import DropZoneV2 from '../../components/DropZoneV2.vue';
import {onMounted, computed, ref, watch} from "vue";
import {useStore} from "vuex";
import env from '../../config/env';
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import Store from "../../store";
import ErrorComponent from '../../components/utils/ErrorComponent.vue';

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  page: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});

const store = useStore();
const dropZoneFile = ref(computed(() => store.getters.getDropZoneFile));
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

watch(dropZoneFile, (value) => {
  if (value !== null && value !== undefined) {
    props.form.attachment = value;
  }
});

onMounted(() => {
  //props.form.business_unit = businessUnit.value;
});

</script>
<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input v-model.trim="form.business_unit"></business-unit-input>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Policy Name <span class="text-red-500">*</span></span>
      <input type="text" v-model.trim="form.name" placeholder="Policy Name" class="form-input" autocomplete="off" required />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Policy Type <span class="text-red-500">*</span></span>
      <select class="form-input" v-model.trim="form.type" autocomplete="off" required>
        <option value="" selected disabled>--Choose an option--</option>
        <option value="Drug">Drug</option>
        <option value="Environment">Environment</option>
      </select>
    </label>
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">
        Attachment
        <template v-if="form.attachment">
           <a class="text-red-700" target="_blank" :href="env.BASE_API_URL+'/'+form?.attachment">{{
            (typeof $props.form?.attachment === 'string')
                ? '('+$props.form?.attachment.split('/').pop()+')'
                : ''
          }}</a>
        </template>
      </span>
      <DropZoneV2 :form="form" :page="page"></DropZoneV2>
    </label>
  </div>
  <ErrorComponent :errors="errors"></ErrorComponent>
</template>

<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}

.input-group {
  @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
}

.label-group {
  @apply block w-full mt-2 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark-disabled:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
}

>>> {
  --vs-controls-color: #374151;
  --vs-border-color: #4b5563;

  --vs-dropdown-bg: #282c34;
  --vs-dropdown-color: #eeeeee;
  --vs-dropdown-option-color: #eeeeee;

  --vs-selected-bg: #664cc3;
  --vs-selected-color: #374151;

  --vs-search-input-color: #4b5563;

  --vs-dropdown-option--active-bg: #664cc3;
  --vs-dropdown-option--active-color: #eeeeee;
}
</style>