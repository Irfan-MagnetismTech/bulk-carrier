<script setup>
import Error from "../Error.vue";
import usePort from "../../composables/usePort";
import { watch } from 'vue';

const { ports, getPortsByNameOrCode } = usePort();

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  voyage: {
    required: false,
    default: {}
  },
  vessels: { type: Array, required: true },
  services: { type: Array, required: true },
  heads: { type: [Object, Array], required: true },
  errors: { type: [Object, Array], required: false }
});

props.form.is_active = 1;


function fetchOptions(search, loading) {
  getPortsByNameOrCode(search);
}

</script>
<template>
  <!-- Basic information -->
  <div class="flex justify-center w-full">
    <label class="block w-full mt-3 text-sm px-3">
      <span class="text-gray-700 dark:text-gray-300">Budget Title <span class="text-red-500">*</span></span>
      <input type="text" v-model="form.title" required placeholder="Ex: Budget Title " class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      <Error v-if="errors?.title" :errors="errors.title" />
    </label>
    <!-- <label class="block w-full mt-3 text-sm px-3">
      <span class="text-gray-700 dark:text-gray-300">Port <span class="text-red-500">*</span></span>
      <v-select v-model="form.port_name" :id="'port_name' + index" @search="fetchOptions" required value="id" :options="ports" label="code_name" placeholder="Enter Port Code or Name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
      <input type="hidden" v-model="form.port" name="port_code" :id="'port_code' + index"/>
      <Error v-if="errors?.port" :errors="errors.port" />
    </label> -->
    <label class="label-group px-3">
        <span class="label-item-title">Vessel <span class="required-style">*</span></span>
        <select v-model="form.vessel_id" class="label-item-input">
          <option value>-- Select Vessel --</option>
          <option v-for="vessel in vessels" :value="vessel.id" :key="vessel.id">{{ vessel.name }}</option>
        </select>
        <Error v-if="errors?.vessel_id" :errors="errors.vessel_id" />
    </label>
    <label class="label-group px-3">
      <span class="label-item-title">Service <span class="required-style">*</span></span>
      <select v-model="form.service_id" class="label-item-input">
        <option value>-- Select Service --</option>
        <option v-for="service in services" :value="service.id" :key="service.id">{{ service.name }}</option>
      </select>
      <Error v-if="errors?.service_id" :errors="errors.service_id" />
    </label>
  </div>
  <div class="flex justify-center w-full">
    <label class="label-group px-3">
                <span class="label-item-title">Status <span class="required-style">*</span></span>
                <select v-model="form.is_active" class="label-item-input capitalize">
                  <option value>-- Select Type --</option>
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
<!--                    <option v-for="(loadtype, index) in loadTypes" :value="loadtype.value" :key="index">{{ loadtype?.label }}</option>-->
                </select>
              <Error v-if="errors?.is_active" :errors="errors.is_active" />
            </label>
    <label class="block w-full mt-3 text-sm px-3">
      <span class="text-gray-700 dark:text-gray-300">Start Date <span class="text-red-500">*</span></span>
      <input type="date" v-model="form.start_date" required placeholder="Ex: BES" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      <Error v-if="errors?.start_date" :errors="errors.start_date" />
    </label>
    <label class="block w-full mt-3 text-sm px-3">
      <span class="text-gray-700 dark:text-gray-300">End Date <span class="text-red-500">*</span></span>
      <input type="date" v-model="form.end_date" required placeholder="Ex: BES" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      <Error v-if="errors?.end_date" :errors="errors.end_date" />
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
  @apply block w-full mt-3 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
}

table th{
  text-transform: capitalize;
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