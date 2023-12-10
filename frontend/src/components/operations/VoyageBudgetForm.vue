<script setup>
import Error from "../Error.vue";
import { watch, ref, onMounted } from 'vue';
// import useVesselVoyageBudget from "../../composables/operations/useVesselVoyageBudget";
import useVoyageBudget from "../../composables/operations/useVoyageBudget";
import useVessel from "../../composables/operations/useVessel";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import ErrorComponent from '../../components/utils/ErrorComponent.vue';
import useVesselExpenseHead from "../../composables/operations/useVesselExpenseHead";
import useVoyage from "../../composables/operations/useVoyage";
import useBusinessInfo from "../../composables/useBusinessInfo";

const { voyageBudgets, getVoyageBudgets, showHead, isLoading, errors } = useVoyageBudget();
const { vessel, vessels, getVesselList, showVessel } = useVessel();
const { voyages, searchVoyages } = useVoyage();
const { vesselExpenseHeads, showVesselWiseExpenseHead } = useVesselExpenseHead();
const { currencies, getCurrencies } = useBusinessInfo();

const subheads = ref([]);

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  formType: { type: String, required : false },
  heads: { type: [Object, Array], required: true },
  errors: { type: [Object, Array], required: false },
});

function fetchVesselExpenseHeads(ops_vessel_id, loading) {
  props.form.heads = []
  showVesselWiseExpenseHead(ops_vessel_id, loading).then(() => {
      if(props.formType == 'create') {
        props.form.heads = vesselExpenseHeads.value
        // props.form.heads = voyageBudgets.value
      }
    })
}

function fetchVesselWiseVoyages(ops_vessel_id, loading) {
  searchVoyages("", props.form.business_unit, loading, ops_vessel_id)
}
watch(() => props.form.opsVessel, (newValue, oldValue) => {
    props.form.ops_vessel_id = null;
    // props.form.ops_vessel_id = newValue?.id;
    voyages.value = []
    props.form.opsVoyage = null;
    props.form.ops_voyage_id = null

    if(newValue){
      props.form.ops_vessel_id = newValue?.id;
      
      fetchVesselExpenseHeads(newValue?.id, false);
      fetchVesselWiseVoyages(props.form.ops_vessel_id, false);
    }

});

watch(() => props.form.business_unit, (newValue, oldValue) => {

  props.form.business_unit = newValue;

  if(newValue !== oldValue && oldValue != null && newValue != undefined){
    props.form.opsVessel = null;
    vessels.value = []
    props.form.heads = []
  }

  vessels.value = []

  if (newValue) {    
    getVesselList(props.form.business_unit);
  }
  
}, {deep: true});

onMounted(() => {
  getCurrencies();
})
</script>
<template>
  <!-- Basic information -->
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <business-unit-input class="w-1/2" v-model="form.business_unit" :page="formType"></business-unit-input>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Vessel <span class="text-red-500">*</span></span>
              <v-select :options="vessels" placeholder="--Choose an option--" v-model="form.opsVessel" label="name" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsVessel"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
              <input type="hidden" v-model="form.ops_vessel_id" />
            </label> 
            <label class="block w-1/2 mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Voyage <span class="text-red-500">*</span></span>
              <v-select :options="voyages" placeholder="--Choose an option--" v-model="form.opsVoyage" label="voyage_sequence" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsVoyage"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
              <input type="hidden" v-model="form.ops_voyage_id" />
            </label> 
  </div>
  <!-- South Sectors -->

  <div class="relative" v-if="form?.heads?.length > 0">

    <div class="dt-responsive table-responsive">
      <table class="w-full whitespace-no-wrap" >
        <thead v-once>
            <tr class="w-full">
              <th class="">Expesne Head</th>
              <th class="">Currency</th>
              <th class="">Unit</th>
              <th>Quantity</th>
              <th>Rate</th>
              <th>Exchange Rate (To USD)</th>
              <th>Exchange Rate (USD To BDT)</th>
              <th>Amount USD</th>
              <th>Amount BDT</th>
            </tr>
          </thead>
          <tbody>
            
            <template v-for="(costGroup, index) in form.heads" :key="index">
              <template v-if="costGroup.opsSubHeads.length > 0">
                <tr v-once>
                  <td colspan="9" class="bg-green-100 font-semibold !text-left">
                      {{ costGroup?.name }}
                  </td>
                </tr>
                <tr v-for="(subhead, subIndex) in costGroup.opsSubHeads" :key="subIndex" class="text-gray-700 dark:text-gray-400">
                  <td class="px-1 py-1">
                    <span class="show-block">
                      {{ subhead?.name }}
                    </span>
                  </td>
                  <td>
                    <select v-model.trim="form.heads[index].opsSubHeads[subIndex].currency" class="form-input" aria-placeholder="Select Currency" placeholder="Select Currency" @change="SetCurrencyData($event,index)">
                      <option selected value="" disabled>Select Currency</option>
                      <option v-for="currency in currencies" :value="currency" :key="currency">{{ currency }}</option>
                    </select>
                  </td>
                </tr>
              </template>
              <template v-else>
                <tr v-once>
                  <td colspan="9" class="bg-green-100 font-semibold !text-left">
                      {{ costGroup?.name }}
                  </td>
                </tr>
                <tr>
                  <td>
                    <span class="show-block">
                      {{ costGroup?.name }}
                    </span>
                  </td>
                </tr>
              </template>
            </template>
            
          </tbody>
        </table>
    </div>

        <!-- <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Cost Group and Cost Heads <span class="text-red-500">*</span></legend>
          <div class="mt-2">
            <div :id="'cost_' + index" :class="index%2===0 ? 'bg-gray-100' : 'bg-yellow-100'" style="position: relative" class="px-2 py-2 border sm:rounded-lg mb-1" v-for="(costGroup, index) in form.heads" :key="index">

              <label class="flex items-center mb-2 text-gray-600 dark-disabled:text-gray-400">
                <input type="checkbox" v-model="form.heads[index].is_checked" class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray">
                <span class="ml-2 font-bold">{{ costGroup.name }}</span>
              </label>

              <template v-for="(subhead,subIndex) in form.heads[index]?.opsSubHeads" :key="subIndex">
                <label class="flex ml-6 items-center mb-2 text-gray-600 dark-disabled:text-gray-400">
                  <input type="checkbox" v-model="form.heads[index].opsSubHeads[subIndex].is_checked" class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray">
                  <span class="ml-2 font-bold">{{ subhead?.name }}</span>
                </label>
              </template>
              
            </div>
          </div>
        </fieldset> -->

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