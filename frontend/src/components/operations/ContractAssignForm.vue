<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700">Assignment Type </span>
          <select v-model="form.contract_assign_type" class="form-input" :disabled="formType=='edit'">
            <option value="" selected disabled>Select Type</option>
            <option value="Customer">Customer</option>
            <option value="Charterer">Charterer</option>
          </select>
      </label>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>

    </div>
    <div class="flex flex-col justify-center w-1/2 md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Assign Date<span class="text-red-500">*</span></span>
              <VueDatePicker v-model="form.assign_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
      </label>

    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Vessel Name <span class="text-red-500">*</span></span>
              <v-select :options="vessels" :readonly="formType=='edit'" :disabled="formType=='edit'" placeholder="--Choose an option--" v-model="form.opsVessel" label="name" class="block form-input" @update:modelValue="opsVesselChange">
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
      <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Voyage <span class="text-red-500">*</span></span>
              <v-select :options="voyages" :readonly="formType=='edit'" :disabled="formType=='edit'" placeholder="--Choose an option--" v-model="form.opsVoyage" label="voyage_sequence" class="block form-input" @update:modelValue="opsVoyageChange">
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

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2" v-if="form.contract_assign_type=='Customer'">
      <!-- <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Tarrif <span class="text-red-500">*</span></span>
              <v-select :options="cargoTariffs" placeholder="--Choose an option--" v-model="form.opsCargoTariff" label="tariff_name" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsCargoTariff"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
      </label> -->
      <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Customer <span class="text-red-500">*</span></span>
              <v-select :options="customers" :disabled="formType=='edit'" placeholder="--Choose an option--" v-model="form.opsCustomer" label="name_code" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsCustomer"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2" v-if="form.contract_assign_type=='Charterer'">
      <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Charterer <span class="text-red-500">*</span></span>
              <v-select :options="chartererProfiles" :disabled="formType=='edit'" placeholder="--Choose an option--" v-model="form.opsChartererProfile" label="name" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsChartererProfile"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
      </label>
      <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Charterer Contract <span class="text-red-500">*</span></span>
              <v-select :options="chartererContracts" placeholder="--Choose an option--" v-model="form.opsChartererContract" label="contract_name" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsChartererContract"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
      </label>
    </div>
    <div v-if="(form.opsVoyage && form.contract_assign_type=='Customer')">
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Sector <span class="text-red-500">*</span></legend>
        <table class="w-full whitespace-no-wrap" id="table">
          <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500  bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
              <th class="w-2/12 px-4 py-3 align-bottom">Loading Point</th>
              <th class="w-2/12 px-4 py-3 align-bottom">Unloading Point</th>
              <th class="w-1/12 px-4 py-3 align-bottom">Quantity</th>
              <th class="w-3/12 px-4 py-3 align-bottom">Tariff <span class="text-red-500">*</span></th>
              <th class="w-1/12 px-4 py-3 align-bottom">Rate</th>
              <th class="w-3/12 px-4 py-3 align-bottom text-center">Month - Total Rate <span class="text-red-500">*</span></th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
            <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(opsContractTariff, index) in form.opsVoyage?.opsContractTariffs" :key="index">
              <td><input type="text" :value="opsContractTariff?.loading_point" placeholder="Loading Point" class="form-input vms-readonly-input" readonly /></td>
              
              <td><input type="text" :value="opsContractTariff?.unloading_point" placeholder="Unloading Point" class="form-input vms-readonly-input" readonly /></td>
              
              <td><input type="text" :value="opsContractTariff?.quantity" placeholder="Unloading Point" class="form-input vms-readonly-input" readonly /></td>
              <td>
                <div v-if="opsContractTariff?.cargoTariffs">
                  <v-select :options="opsContractTariff?.cargoTariffs" placeholder="--Choose an option--" v-model="opsContractTariff.opsCargoTariff" label="tariff_name" class="block form-input" @update:modelValue="opsCargoTariffChange(opsContractTariff)">
                    <template #search="{attributes, events}">
                      <input
                      class="vs__search"
                      :required="!opsContractTariff.opsCargoTariff"
                      v-bind="attributes"
                      v-on="events"
                      />
                    </template>
                  </v-select>
                  <input type="hidden" v-model="opsContractTariff.ops_cargo_tariff_id" />
                </div>
                <span class="text-red-600 font-bold" v-else>Cargo tariff not found</span>
              </td>
              <td><input type="text" v-model="opsContractTariff.total_rate"   placeholder="Rate" class="form-input vms-readonly-input" readonly /></td>
              <td>
                <div v-if="opsContractTariff?.ops_cargo_tariff_id">
                  <select class="form-input" v-model="opsContractTariff.tariff_month" autocomplete="off" required @change = "opsTariffMonthChange(opsContractTariff)" >
                    <option  value="" disabled selected>Select</option>
                    <option v-if="opsContractTariff.ops_cargo_tariff_id" value="jan"> Jan - {{opsContractTariff?.opsCargoTariff?.["jan"]}} </option>
                    <option v-if="opsContractTariff.ops_cargo_tariff_id" value="feb"> Feb - {{opsContractTariff?.opsCargoTariff?.["feb"]}} </option>
                    <option v-if="opsContractTariff.ops_cargo_tariff_id" value="mar"> Mar - {{opsContractTariff?.opsCargoTariff?.["mar"]}} </option>
                    <option v-if="opsContractTariff.ops_cargo_tariff_id" value="apr"> Apr - {{opsContractTariff?.opsCargoTariff?.["apr"]}} </option>
                    <option v-if="opsContractTariff.ops_cargo_tariff_id" value="may"> May - {{opsContractTariff?.opsCargoTariff?.["may"]}} </option>
                    <option v-if="opsContractTariff.ops_cargo_tariff_id" value="jun"> Jun - {{opsContractTariff?.opsCargoTariff?.["jun"]}} </option>
                    <option v-if="opsContractTariff.ops_cargo_tariff_id" value="jul"> Jul - {{opsContractTariff?.opsCargoTariff?.["jul"]}} </option>
                    <option v-if="opsContractTariff.ops_cargo_tariff_id" value="aug"> Aug - {{opsContractTariff?.opsCargoTariff?.["aug"]}} </option>
                    <option v-if="opsContractTariff.ops_cargo_tariff_id" value="sep"> Sep - {{opsContractTariff?.opsCargoTariff?.["sep"]}} </option>
                    <option v-if="opsContractTariff.ops_cargo_tariff_id" value="oct"> Oct - {{opsContractTariff?.opsCargoTariff?.["oct"]}} </option>
                    <option v-if="opsContractTariff.ops_cargo_tariff_id" value="nov"> Nov - {{opsContractTariff?.opsCargoTariff?.["nov"]}} </option>
                    <option v-if="opsContractTariff.ops_cargo_tariff_id" value="dec"> Dec - {{opsContractTariff?.opsCargoTariff?.["dec"]}} </option>
                  </select>
                </div>
                <span v-else class="text-red-600 font-bold">
                  Select tariff
                </span>
              </td>

            </tr>
          </tbody>
        </table>
      </fieldset>
    </div>
    <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<script setup>
import { ref, watch, onMounted, watchPostEffect, watchEffect } from "vue";
import Error from "../Error.vue";
import useVoyage from "../../composables/operations/useVoyage";
import useVessel from "../../composables/operations/useVessel";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import useChartererProfile from "../../composables/operations/useChartererProfile";
import useChartererContract from "../../composables/operations/useChartererContract";
import ErrorComponent from '../../components/utils/ErrorComponent.vue';
import useBusinessInfo from '../../composables/useBusinessInfo';
import LoaderComponent from "../../components/utils/LoaderComponent.vue";
import moment from 'moment';
import cloneDeep from 'lodash/cloneDeep';
import useCustomer from "../../composables/operations/useCustomer";
import useCargoTariff from "../../composables/operations/useCargoTariff";
import Swal from 'sweetalert2';

const editInitiated = ref(false);
const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date);

const { currencies, getCurrencies } = useBusinessInfo();
const { getChartererByBusinessUnit, chartererProfiles } = useChartererProfile();
const { getChartererContractsByCharterOwner, chartererContracts } = useChartererContract();
const { voyage, voyages, showVoyage, getVoyageList,searchVoyages } = useVoyage();
const { vessel, vessels, getVesselList, showVessel } = useVessel();
const { getCustomersByBusinessUnit, customers } = useCustomer();
const { getCargoTariffsByVessel, cargoTariffs, getCargoTariffsByVoyage } = useCargoTariff();

const props = defineProps({
    form: { required: false,default: {},},
    errors: { type: [Object, Array], required: false },
    formType: { type: String, required: false },
    serviceObject: { type: Object, required: false },
    loading: { type: Boolean, required: false },
    otherObject: { type: Object, required: false },
    
});


watch(() => props.form.business_unit, (value) => {
if(props?.formType != 'edit') {
  cleanThings()
  vessels.value = [];

  if(props.form.contract_assign_type == 'Charterer') {
      getChartererByBusinessUnit(props.form.business_unit);
    } else if(props.form.contract_assign_type == 'Customer') {
      getCustomersByBusinessUnit(props.form.business_unit);
    }
}

getVesselList(props.form.business_unit);

}, { deep : true })

watch(() => props.form.contract_assign_type, (value) => {

  if(props.form.business_unit == '' || props.form.business_unit =='ALL' || props.form.business_unit == null) {
    Swal.fire({
                    icon: "",
                    title: "Correct Please!",
                    html: "Please add business unit first",
                    customClass: "swal-width",
                });
                return false;
  }

  if(props?.formType != 'edit') {
    cleanThings()

    if(props.form.contract_assign_type == 'Charterer') {
      getChartererByBusinessUnit(props.form.business_unit);
    } else if(props.form.contract_assign_type == 'Customer') {
      getCustomersByBusinessUnit(props.form.business_unit);
    }
  }


}, { deep: true });

function cleanThings() {
  props.form.opsVoyage = null;
  props.form.ops_voyage_id = null;
  props.form.opsVessel = null;
  props.form.ops_vessel_id = null;
  chartererProfiles.value = []
  chartererContracts.value = []
  customers.value = []
  props.form.opsCustomer = null;
  props.form.ops_customer_id = null;
  props.form.opsCargoTariff = null;
  props.form.ops_cargo_tariff_id = null;
  props.form.opsChartererProfile = null;
  props.form.ops_charterer_profile_id = null;
}

onMounted(() => {
  watchPostEffect(() => {
    getVesselList(props.form.business_unit);
    
  })
 
})

// watch(() => props.form.opsVoyage, (value) => {
// if(value) {
//   props.form.ops_voyage_id = value?.id
//   }
// }, { deep: true })

function opsVoyageChange() {
  const value = props.form.opsVoyage;
  props.form.ops_voyage_id = value?.id;
  if(props.form.business_unit && props.form.ops_voyage_id)
    getCargoTariffsByVoyage(props.form.business_unit, props.form.ops_voyage_id);
}

// watch(() => props.form.opsVessel, (value) => {

// if(value) {
//   props.form.ops_vessel_id = value?.id

//   voyages.value = [];
//   props.form.opsVoyage = null;

//   let loadStatus = true;
//   showVessel(value?.id, loadStatus);
//   getVoyageList(props.form.business_unit, props.form.ops_vessel_id);
//   getCargoTariffsByVessel(props.form.ops_vessel_id);
// }
// }, { deep: true })

function opsVesselChange() {
  const value = props.form.opsVessel;
  props.form.ops_vessel_id = value?.id
  
  voyages.value = [];
  props.form.opsVoyage = null;
  
  if(value) {
    let loadStatus = true;
    showVessel(value?.id, loadStatus);
    getVoyageList(props.form.business_unit, props.form.ops_vessel_id);
    // getCargoTariffsByVessel(props.form.ops_vessel_id);
    chartererContracts.value = []

    getChartererContract()
  }
}


watch(() => props.form.opsChartererProfile, (value) => {
    props.form.ops_charterer_profile_id = value?.id;
    chartererContracts.value = []
    getChartererContract()
})


function getChartererContract() {
  // if(props.form.ops_charterer_profile_id == null || props.form.ops_vessel_id == null) {
  //   Swal.fire({
  //                   icon: "",
  //                   title: "Correct Please!",
  //                   html: "Please add business unit first",
  //                   customClass: "swal-width",
  //               });
  //               return false;
  // }

  getChartererContractsByCharterOwner(props.form.ops_charterer_profile_id, props.form.ops_vessel_id, 'Active');

}


// watch(() => props.form.ops_charterer_profile_id, (value) => {
//     // props.form.ops_charterer_contract_id = '';
//     // props.form.opsChartererContract = null;
//     // getChartererContractsByCharterOwner(value);
// })


watch(() => props.form.opsChartererContract, (value) => {
  props.form.ops_charterer_contract_id = value?.id;
  props.form.contract_type = value?.contract_type;
})

//opsCustomer
watch(() => props.form.opsCustomer, (value) => {
  props.form.ops_customer_id = value?.id;
})

watch(() => props.form.opsCargoTariff, (value) => {
  props.form.ops_cargo_tariff_id = value?.id;
})



function opsCargoTariffChange(opsContractTariff) {
  opsContractTariff.ops_cargo_tariff_id = opsContractTariff?.opsCargoTariff?.id;

  if (!opsContractTariff.tariff_month && opsContractTariff.ops_cargo_tariff_id)
    opsContractTariff.tariff_month = moment(props.form.opsVoyage.sail_date).format('MMM').toLowerCase();
  else
    opsContractTariff.tariff_month = '';

  opsTariffMonthChange(opsContractTariff)

}

function opsTariffMonthChange(opsContractTariff) {
  opsContractTariff.total_rate = opsContractTariff.tariff_month ? opsContractTariff?.opsCargoTariff?.[opsContractTariff.tariff_month] : '';
}
</script>
<style lang="postcss" scoped>
.input-group {
  @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
}
.label-group {
  @apply block w-full mt-3 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark-disabled:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray;
}
</style>