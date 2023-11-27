<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>

    </div>

    <h4 class="text-md font-semibold mt-3">Basic Info</h4>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Vessel </span>
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
      <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Voyage </span>
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

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2" v-if="form.business_unit=='TSLL'">
      <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Tarrif </span>
              <v-select :options="cargoTariffs" placeholder="--Choose an option--" v-model="form.opsTariff" label="tariff_name" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsTariff"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
              <input type="hidden" v-model="form.ops_vessel_id" />
      </label>
      <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Customer </span>
              <v-select :options="customers" placeholder="--Choose an option--" v-model="form.opsCustomer" label="name_code" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsCustomer"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
              <input type="hidden" v-model="form.ops_voyage_id" />
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2" v-if="form.business_unit=='PSML'">
      <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Charterer </span>
              <v-select :options="chartererProfiles" placeholder="--Choose an option--" v-model="form.opsChartererProfile" label="name" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsChartererProfile"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
              <input type="hidden" v-model="form.ops_vessel_id" />
      </label>
      <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Charterer Contract </span>
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
              <input type="hidden" v-model="form.ops_voyage_id" />
      </label>
    </div>

    
</template>
<script setup>
import { ref, watch, onMounted, watchPostEffect, watchEffect } from "vue";
import Error from "../Error.vue";
import useVoyage from "../../composables/operations/useVoyage";
import useVessel from "../../composables/operations/useVessel";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import useChartererProfile from "../../composables/operations/useChartererProfile";
import useChartererContract from "../../composables/operations/useChartererContract";

import useBusinessInfo from '../../composables/useBusinessInfo';

import moment from 'moment';
import cloneDeep from 'lodash/cloneDeep';
import useCustomer from "../../composables/operations/useCustomer";
import useCargoTariff from "../../composables/operations/useCargoTariff";

const editInitiated = ref(false);

const { currencies, getCurrencies } = useBusinessInfo();
const { getChartererByBusinessUnit, chartererProfiles } = useChartererProfile();
const { getChartererContractsByCharterOwner, chartererContracts } = useChartererContract();
const { voyage, voyages, showVoyage, getVoyageList,searchVoyages } = useVoyage();
const { vessel, vessels, getVesselList, showVessel } = useVessel();
const { getCustomersByBusinessUnit, customers } = useCustomer();
const { getCargoTariffsByVessel, cargoTariffs } = useCargoTariff();

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
  props.form.opsVoyage = null;
  props.form.ops_voyage_id = null;
  props.form.opsVessel = null;
  props.form.ops_vessel_id = null;
}

getVesselList(props.form.business_unit);

}, { deep : true })

onMounted(() => {
  watchPostEffect(() => {
    getVesselList(props.form.business_unit);
  getChartererByBusinessUnit(props.form.business_unit);
  getCustomersByBusinessUnit(props.form.business_unit);
  })
 
})

watch(() => props.form.opsVoyage, (value) => {
if(value) {
  props.form.ops_voyage_id = value?.id
  }
}, { deep: true })

watch(() => props.form.opsVessel, (value) => {

if(value) {
  props.form.ops_vessel_id = value?.id
  let loadStatus = true;
  showVessel(value?.id, loadStatus);
  getVoyageList(props.form.business_unit, props.form.ops_vessel_id);
  getCargoTariffsByVessel(props.form.ops_vessel_id);
}
}, { deep: true })

watch(() => props.form.opsChartererProfile, (value) => {
    props.form.ops_charterer_profile_id = value?.id;
})



watch(() => props.form.opsChartererProfile, (value) => {
    props.form.ops_charterer_profile_id = value?.id;
})





watch(() => props.form.ops_charterer_profile_id, (value) => {
    props.form.ops_charterer_contract_id = '';
    props.form.opsChartererContract = null;
    getChartererContractsByCharterOwner(value);
})


watch(() => props.form.opsChartererContract, (value) => {
  props.form.ops_charterer_contract_id = value?.id;
  props.form.contract_type = value?.contract_type;
})

//opsCustomer
watch(() => props.form.opsCustomer, (value) => {
  props.form.ops_customer_id = value?.id;
})

watch(() => props.form.opsTariff, (value) => {
  props.form.ops_tariff_id = value?.id;
})

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