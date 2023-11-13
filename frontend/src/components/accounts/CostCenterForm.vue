<script setup>
import Error from "../Error.vue";
import {onMounted, ref} from "vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import Store from "../../store";

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

const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

function checkWhitespace(value) {
  if (/^\s+$/.test(props.form.name)) {props.form.name = '';}
  if (/^\s+$/.test(props.form.short_name)) {props.form.short_name = '';}
}

onMounted(() => {
  //props.form.business_unit = businessUnit.value;
});

</script>
<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Cost Center Name <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.name" @input="checkWhitespace" v-model="form.name" placeholder="Cost center name" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.name" :errors="errors.name" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Short Name <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.short_name" @input="checkWhitespace" placeholder="Short name" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.short_name" :errors="errors.short_name" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Type <span class="text-red-500">*</span></span>
        <select class="form-input" v-model="form.type" autocomplete="off" required>
          <option value="" disabled selected>Select</option>
          <option value="Lighter Vessel">Lighter Vessel</option>
          <option value="Bulk Carrier">Bulk Carrier</option>
          <option value="Head Office">Head Office</option>
        </select>
        <Error v-if="errors?.type" :errors="errors.type" />
      </label>
    </div>
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
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
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