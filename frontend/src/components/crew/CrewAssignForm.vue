<script setup>
import Error from "../Error.vue";
import useVessel from "../../composables/operations/useVessel";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import {onMounted, ref, watch, watchEffect} from "vue";
import useCommonApiRequest from "../../composables/crew/useCommonApiRequest";
import Store from "../../store";

const { vessels, searchVessels } = useVessel();
const { crwRankLists, getCrewRankLists } = useCommonApiRequest();
const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

function fetchVessels(search, loading) {
  loading(true);
  searchVessels(search, loading)
}

watch(() => props.form, (value) => {
  if(value){
    props.form.ops_vessel_id = props.form?.ops_vessel_name?.id ?? '';
  }
}, {deep: true});

onMounted(() => {
  props.form.business_unit = businessUnit.value;
  watchEffect(() => {
    getCrewRankLists(props.form.business_unit);
  });
});

</script>

<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input v-model="form.business_unit"></business-unit-input>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Vessel Name <span class="text-red-500">*</span></span>
        <v-select :options="vessels" placeholder="--Choose an option--" @search="fetchVessels"  v-model="form.ops_vessel_name" label="name" class="block form-input">
          <template #search="{attributes, events}">
            <input
                class="vs__search"
                :required="!form.ops_vessel_name"
                v-bind="attributes"
                v-on="events"
            />
          </template>
        </v-select>
        <Error v-if="errors?.ops_vessel_name" :errors="errors.ops_vessel_name" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Effective Date <span class="text-red-500">*</span></span>
        <input type="date" v-model="form.effective_date" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.effective_date" :errors="errors.effective_date" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Total Crew <span class="text-red-500">*</span></span>
        <input type="number" v-model="form.total_crew" placeholder="Ex: 14" class="form-input" autocomplete="off" />
        <Error v-if="errors?.total_crew" :errors="errors.total_crew" />
      </label>
    </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Remarks</span>
      <input type="text" v-model="form.remarks" placeholder="Remarks" class="form-input" autocomplete="off" />
      <Error v-if="errors?.remarks" :errors="errors.remarks" />
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