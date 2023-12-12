<script setup>
import Error from "../Error.vue";
import useVessel from "../../composables/operations/useVessel";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import {onMounted, ref, watch, watchEffect} from "vue";
import useCrewCommonApiRequest from "../../composables/crew/useCrewCommonApiRequest";
import Store from "../../store";
import ErrorComponent from '../../components/utils/ErrorComponent.vue';
import RemarksComponent from "../utils/RemarksComponent.vue";
import useHeroIcon from "../../assets/heroIcon";
const icons = useHeroIcon();

const { vessels, searchVessels, getVesselsWithoutPaginate, isLoading } = useVessel();
const { crwRankLists, getCrewRankLists } = useCrewCommonApiRequest();
const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

function addItem() {
  let obj = {
    crw_rank_id: '',
    required_manpower: '',
    eligibility: '',
    remarks: '',
    isRankNameDuplicate: false,
  };
  props.form.crwVesselRequiredCrewLines.push(obj);
}

function removeItem(index){
  props.form.crwVesselRequiredCrewLines.splice(index, 1);
}

watch(() => props.form, (value) => {
  if(value){
    props.form.ops_vessel_id = props.form?.ops_vessel_name?.id ?? '';
  }
}, {deep: true});

watch(() => props.form.business_unit, (newValue, oldValue) => {
  businessUnit.value = newValue;
  if(newValue !== oldValue && oldValue != ''){
    props.form.ops_vessel_name = null;
    props.form.ops_vessel_id = '';
  }
});

watch(
    () => props.form,
    (newEntries, oldEntries) => {

      if(newEntries?.crwVesselRequiredCrewLines?.length > 0) {
        let total_manpower = 0.0;
        newEntries?.crwVesselRequiredCrewLines?.forEach((item) => {
          if(item.required_manpower) {
            total_manpower += parseFloat(item.required_manpower);
          }
        });
        if(!isNaN(total_manpower)) {
          props.form.total_crew = total_manpower;
        }
      }
    },
    { deep: true }
);

onMounted(() => {
  watchEffect(() => {
    getVesselsWithoutPaginate(props.form.business_unit);
    getCrewRankLists(props.form.business_unit);
  });
});

</script>

<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input v-model.trim="form.business_unit"></business-unit-input>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Name <span class="text-red-500">*</span></span>
        <v-select :options="vessels" :loading="isLoading" placeholder="--Choose an option--"  v-model.trim="form.ops_vessel_name" label="name" class="block form-input">
          <template #search="{attributes, events}">
            <input
                class="vs__search"
                :required="!form.ops_vessel_name"
                v-bind="attributes"
                v-on="events"
            />
          </template>
        </v-select>
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Effective Date <span class="text-red-500">*</span></span>
        <input type="date" v-model.trim="form.effective_date" class="form-input" autocomplete="off" required />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Total Crew <span class="text-red-500">*</span></span>
        <input type="number" v-model.trim="form.total_crew" placeholder="Ex: 14" class="form-input vms-readonly-input" readonly autocomplete="off" required />
      </label>
    </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <RemarksComponent v-model.trim="form.remarks" :maxlength="500" :fieldLabel="'Remarks'"></RemarksComponent>
  </div>
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
    <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Required Crew List</legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
        <th class="px-4 py-3 align-bottom w-64">Rank <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom w-32">Required Manpower <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Eligibility <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Remarks</th>
        <th class="px-4 py-3 text-center align-bottom">Action</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
      <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(requiredCrewLine, index) in form.crwVesselRequiredCrewLines" :key="requiredCrewLine.id">
        <td class="px-1 py-1">
          <div style="position: relative;">
            <select class="form-input" v-model.trim="form.crwVesselRequiredCrewLines[index].crw_rank_id" required>
              <option value="" disabled>Select</option>
              <option v-for="(crwRank,index) in crwRankLists" :value="crwRank.id">{{ crwRank?.name }}</option>
            </select>
            <span
                v-show="requiredCrewLine.isRankNameDuplicate"
                class="text-yellow-600 pl-1"
                title="Duplicate Rank Name"
                v-html="icons.ExclamationTriangle"
                style="position: absolute; top: 50%; transform: translateY(-50%); right: 30px;"
            ></span>
          </div>
<!--          <span-->
<!--              v-show="requiredCrewLine.isRankNameDuplicate"-->
<!--              class="text-yellow-600 pl-1"-->
<!--              title="Duplicate Item Name"-->
<!--              v-html="icons.ExclamationTriangle"-->
<!--          ></span>-->
        </td>
        <td class="px-1 py-1">
          <input type="number" v-model.trim="form.crwVesselRequiredCrewLines[index].required_manpower" placeholder="Ex: 2" class="form-input" required autocomplete="off" />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model.trim="form.crwVesselRequiredCrewLines[index].eligibility" placeholder="Ex: COC-III" required class="form-input" autocomplete="off" />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model.trim="form.crwVesselRequiredCrewLines[index].remarks" placeholder="Remarks" class="form-input" autocomplete="off" />
        </td>
        <td class="px-1 py-1 text-center">
          <button v-if="index!==0" type="button" @click="removeItem(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
          </button>
          <button v-else type="button" @click="addItem()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
          </button>
        </td>
      </tr>
      </tbody>
    </table>
  </fieldset>
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