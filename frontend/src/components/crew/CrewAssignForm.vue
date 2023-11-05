<script setup>
import Error from "../Error.vue";
import useVessel from "../../composables/operations/useVessel";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import {onMounted, ref, watch, watchEffect} from "vue";
import useCrewCommonApiRequest from "../../composables/crew/useCrewCommonApiRequest";
import Store from "../../store";
import usePort from "../../composables/operations/usePort";

const { vessels, getVesselsWithoutPaginate } = useVessel();
const { crews, getCrews, crwRankLists, getCrewRankLists } = useCrewCommonApiRequest();
const { ports, searchPorts, isLoading } = usePort();
const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

watch(() => props.form, (value) => {
  if(value){
    props.form.ops_vessel_id = props.form?.ops_vessel_name?.id ?? '';
    props.form.ops_vessel_flag = props.form?.ops_vessel_name?.flag;

    //for crew
    props.form.crw_crew_id = props.form?.crw_crew_name?.id ?? '';

    //for port
    props.form.port_of_joining = props.form?.port_of_joining_name?.code ?? '';
  }
}, {deep: true});

function fetchPorts(search, loading) {
  loading(true);
  searchPorts(search, loading)
}

onMounted(() => {
  props.form.business_unit = businessUnit.value;
  watchEffect(() => {
    getVesselsWithoutPaginate(props.form.business_unit);
    getCrews(props.form.business_unit);
    getCrewRankLists(props.form.business_unit);
  });
});

</script>

<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input v-model="form.business_unit"></business-unit-input>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Vessel Name <span class="text-red-500">*</span></span>
        <v-select :options="vessels" placeholder="--Choose an option--"  v-model="form.ops_vessel_name" label="name" class="block form-input">
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
        <span class="text-gray-700 dark:text-gray-300">Vessel Flag </span>
        <input type="text" v-model="form.ops_vessel_flag" class="form-input vms-readonly-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Crew Name <span class="text-red-500">*</span></span>
        <v-select :options="crews" placeholder="--Choose an option--"  v-model="form.crw_crew_name" label="name" class="block form-input">
          <template #search="{attributes, events}">
            <input
                class="vs__search"
                :required="!form.crw_crew_name"
                v-bind="attributes"
                v-on="events"
            />
          </template>
        </v-select>
        <Error v-if="errors?.crw_crew_name" :errors="errors.crw_crew_name" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Position Onboard/Joining Rank <span class="text-red-500">*</span></span>
        <v-select :options="crwRankLists" placeholder="--Choose an option--"  v-model="form.position_onboard" :reduce="crwRankLists => crwRankLists.name" label="name" class="block form-input">
          <template #search="{attributes, events}">
            <input
                class="vs__search"
                :required="!form.position_onboard"
                v-bind="attributes"
                v-on="events"
            />
          </template>
        </v-select>
        <Error v-if="errors?.position_onboard" :errors="errors.position_onboard" />
      </label>
    </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Date of Joining <span class="text-red-500">*</span></span>
      <input type="date" v-model="form.date_of_joining" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.date_of_joining" :errors="errors.date_of_joining" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Port of Joining <span class="text-red-500">*</span></span>
      <v-select :options="ports" placeholder="--Choose an option--" @search="fetchPorts" v-model="form.port_of_joining_name" label="name" class="block form-input">
        <template #search="{attributes, events}">
          <input
              class="vs__search"
              :required="!form.port_of_joining_name"
              v-bind="attributes"
              v-on="events"
          />
        </template>
      </v-select>
      <Error v-if="errors?.port_of_joining_name" :errors="errors.port_of_joining_name" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Duration in Months <span class="text-red-500">*</span></span>
      <input type="number" v-model="form.approx_duration" placeholder="Ex: 6" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.approx_duration" :errors="errors.approx_duration" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Remarks </span>
      <input type="text" v-model="form.remarks" class="form-input" autocomplete="off" placeholder="Remarks" />
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