<template>

    <div id="basic-info">
      <h4 class="text-md font-semibold">Basic Info</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
          <label class="block w-full mt-2 text-sm"></label>
          <label class="block w-full mt-2 text-sm"></label>
          <label class="block w-full mt-2 text-sm"></label>

      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Contract Name <span class="text-red-500">*</span></span>
              <input type="text" v-model.trim="form.contract_name" required placeholder="Contract Name" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Contract Type <span class="text-red-500">*</span></span>
              <select :disabled="formType=='edit'" required v-model="form.contract_type" class="form-input">
                <option value="" selected disabled>Select</option>
                <option>Voyage Wise</option>
                <option>Day Wise</option>
              </select>
          </label>
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
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
              <input type="hidden" v-mode="form.ops_vessel_id" />
          </label>

          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Owner</span>
              <input type="text" readonly v-model="form.vessel_owner" placeholder="Vessel Owner" class="form-input bg-gray-100" autocomplete="off" />
          </label>

      </div>

      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Select Charterer <span class="text-red-500">*</span></span>
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
              <input type="hidden" v-model.trim="form.ops_charterer_profile_id" />

          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Charterer Owner Code</span>
              <input type="text" v-model.trim="form.charterer_code" placeholder="Charterer Owner Code" class="form-input bg-gray-100" readonly autocomplete="off" />
          </label>
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
       
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Country</span>
              <input type="text" v-model.trim="form.country" placeholder="Country" class="form-input" autocomplete="off" />
          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Address</span>
            <input type="text" v-model.trim="form.address" placeholder="Address" class="form-input" autocomplete="off" />
        </label>
        
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Billing Address</span>
              <input type="text" v-model.trim="form.billing_address" placeholder="Billing Address" class="form-input" autocomplete="off" />
          </label>
        
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Email</span>
              <input type="text" v-model.trim="form.email" placeholder="Email" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Contact No.</span>
            <input type="text" v-model.trim="form.contact_no" placeholder="Contact No." class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Attachment </span>
              <input type="file" @change="attachFile" placeholder="Billing Email" class="form-input" autocomplete="off" />
          </label>
      </div>
    </div>
    
    <div id="bank-account-info">
      <h4 class="text-md font-semibold mt-4">Bank Account Info</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Bank Name</span>
              <input type="text" v-model.trim="form.bank_name" placeholder="Bank Name" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Bank Branch </span>
              <input type="text" v-model.trim="form.bank_branch_name" placeholder="Bank Branch" class="form-input" autocomplete="off" />
          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Account No</span>
            <input type="text" v-model.trim="form.bank_account_no" placeholder="Account No" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Account Name</span>
              <input type="text" v-model.trim="form.bank_account_name" placeholder="Account Name" class="form-input" autocomplete="off" />
        </label>
          
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Swift Code</span>
              <input type="text" v-model.trim="form.swift_code" placeholder="Swift Code" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Routing No</span>
            <input type="text" v-model.trim="form.routing_no" placeholder="Routing No" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Currency <span class="text-red-500">*</span></span>
                <select v-model="form.currency" class="form-input" required>
                  <option value="">Select Currency</option>
                  <option v-for="currency in currencies">{{ currency }}</option>
                </select>
        </label>
        <label class="block w-full mt-2 text-sm"></label>
      </div>
    </div>

    <div id="local-agent-info">
      <h4 class="text-md font-semibold mt-4">Local Agent Info</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Port <span class="text-red-500">*</span></span>
              <v-select :options="ports" placeholder="--Choose an option--" v-model="form.opsChartererContractsLocalAgents[0].opsPort" label="code_name" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsChartererContractsLocalAgents[0].opsPort"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>

              <input type="hidden" v-model="form.opsChartererContractsLocalAgents[0].port_code" />
          </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Agent Name</span>
              <input type="text" v-model.trim="form.opsChartererContractsLocalAgents[0].agent_name" placeholder="Agent Name" class="form-input" autocomplete="off" />
        </label>
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Billing Name </span>
              <input type="text" v-model.trim="form.opsChartererContractsLocalAgents[0].agent_billing_name" placeholder="Billing Name" class="form-input" autocomplete="off" />
          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Billing Email</span>
            <input type="text" v-model.trim="form.opsChartererContractsLocalAgents[0].agent_billing_email" placeholder="Billing Email" class="form-input" autocomplete="off" />
        </label>
      </div>
    </div>

    <div id="contract-validity">
      <h4 class="text-md font-semibold mt-4">Contract Validity and Billing</h4>
      <div v-if="form.contract_type == 'Day Wise'" class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Credit Days</span>
              <input type="number" v-model.trim="form.opsChartererContractsFinancialTerms.credit_days" placeholder="Credit Days" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Billing Cycle </span>
              <input type="text" v-model.trim="form.opsChartererContractsFinancialTerms.billing_cycle" placeholder="Billing Cycle" class="form-input" autocomplete="off" />
          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Valid From <span class="text-red-500">*</span></span>
            <VueDatePicker v-model="form.opsChartererContractsFinancialTerms.valid_from" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>

        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Valid Till <span class="text-red-500">*</span></span>
            <VueDatePicker v-model="form.opsChartererContractsFinancialTerms.valid_till" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
        </label>
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <!-- <div v-if="form.contract_type == 'Voyage Wise'" class="w-full">
            <label class="block w-full mt-2 text-sm">
                <span class="text-gray-700 dark-disabled:text-gray-300">Cargo Tariff <span class="text-red-500">*</span></span>
                <v-select :options="cargoTariffs" placeholder="--Choose an option--" v-model="form.opsChartererContractsFinancialTerms.opsCargoTariff" label="tariff_name" class="block form-input">
                    <template #search="{attributes, events}">
                        <input
                            class="vs__search"
                            :required="!form.opsChartererContractsFinancialTerms.opsCargoTariff"
                            v-bind="attributes"
                            v-on="events"
                            />
                    </template>
                </v-select>
                <input type="hidden" v-model="form.opsChartererContractsFinancialTerms.ops_cargo_tariff_id" />
            </label>
          </div> -->
        
            <label class="block mt-2 text-sm w-1/2">

              <span class="text-gray-700 dark-disabled:text-gray-300"> Status</span>
                <select v-model="form.status" class="form-input">
                  <option value="">Select Status</option>
				          <option>Active</option>
				          <option>Inactive</option>
				        </select>
          </label>
          <label v-if="form.contract_type == 'Voyage Wise'" class="block w-1/2 mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Approximate Load Amount</span>
            <input type="number" v-model.trim="form.opsChartererContractsFinancialTerms.approximate_load_amount" placeholder="Approximate Load Amount" class="form-input" autocomplete="off" />
          </label>
          <label v-else class="block w-1/2 mt-2 text-sm"></label>
          <label class="block w-full mt-2 text-sm"></label>
        </div>
    </div>

    <div id="rates-fess">
      <h4 class="text-md font-semibold mt-4">Rates and Fees</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label v-if="form.contract_type == 'Day Wise'" class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Per Day Charge <span class="text-red-500">*</span></span>
              <input type="number" step="0.001" required v-model.trim="form.opsChartererContractsFinancialTerms.per_day_charge" placeholder="Per Day Charge" class="form-input" autocomplete="off" />
        </label>
        <label v-if="form.contract_type == 'Voyage Wise'" class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Per MT Charge <span class="text-red-500">*</span></span>
              <input type="number" step="0.001" required v-model.trim="form.opsChartererContractsFinancialTerms.per_ton_charge" placeholder="Per MT Charge" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Cleaning Fee </span>
              <input type="number" step="0.001" v-model.trim="form.opsChartererContractsFinancialTerms.cleaning_fee" placeholder="Cleaning Fee" class="form-input" autocomplete="off" />
          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Cancellation Fee <small>(%)</small></span>
            <input type="text" v-model.trim="form.opsChartererContractsFinancialTerms.cancellation_fee" placeholder="Cancellation Fee" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Others Fee</span>
            <input type="number" step="0.001" v-model.trim="form.opsChartererContractsFinancialTerms.others_fee" placeholder="Others Fee" class="form-input" autocomplete="off" />
        </label>
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300 font-semibold">Bunker Provider</span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <input type="radio" v-model.trim="form.opsChartererContractsFinancialTerms.bunker_provider" name="bunker_provider" value="Ship Owner" />
            <span class="text-gray-700 dark-disabled:text-gray-300 ml-2">By Ship Owner </span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <input type="radio" v-model.trim="form.opsChartererContractsFinancialTerms.bunker_provider" name="bunker_provider" value="By Charterer" />
            <span class="text-gray-700 dark-disabled:text-gray-300 ml-2">By Charterer</span>
        </label>
        <label class="block w-full mt-2 text-sm"></label>
      </div>
    </div>
    <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<script setup>
import { ref, watch, onMounted } from "vue";
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import useBusinessInfo from "../../composables/useBusinessInfo"
import useVessel from "../../composables/operations/useVessel";
import usePort from "../../composables/operations/usePort";
import useChartererProfile from "../../composables/operations/useChartererProfile";
import ErrorComponent from '../../components/utils/ErrorComponent.vue';
import useCargoTariff from "../../composables/operations/useCargoTariff";

const editInitiated = ref(false);
const { getCurrencies, currencies } = useBusinessInfo();
const { vessel, vessels, getVesselList, showVessel } = useVessel();
const { ports, searchPorts } = usePort();
const { getAllChartererProfiles, chartererProfiles } = useChartererProfile();
const { cargoTariffs, getAllCargoTariffs } = useCargoTariff();
const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date);

const props = defineProps({
    form: {
        required: false,
        default: {},
    },
    errors: { type: [Object, Array], required: false },
    formType: { type: String, required : false }
});


function attachFile(e) {
    let fileData = e.target.files[0];
    props.form.attachment = fileData;
}

watch(() => props.form.business_unit, (value) => {
  if(props?.formType != 'edit') {
    props.form.opsVessel = null;
    vessels.value = []
    props.form.ops_vessel_id = null;
    props.form.vessel_owner = ''
    props.form.opsChartererProfile = null;
    props.form.ops_charterer_profile_id = null;
    // props.form.opsChartererContractsFinancialTerms.opsCargoTariff = null;
    // props.form.opsChartererContractsFinancialTerms.ops_cargo_tariff_id = null;
  }

  getVesselList(props.form.business_unit);
  getAllChartererProfiles(props.form.business_unit);
  getAllCargoTariffs(props.form.business_unit);
  searchPorts("", props.form.business_unit);
  
}, { deep : true })


watch(() => props.form.contract_type, (value) => {
      if(props?.formType != 'edit') {
          props.form.opsChartererContractsFinancialTerms.credit_days = null;
          props.form.opsChartererContractsFinancialTerms.billing_cycle = '';
          props.form.opsChartererContractsFinancialTerms.valid_from = null;
          props.form.opsChartererContractsFinancialTerms.valid_till = null;
          props.form.opsChartererContractsFinancialTerms.per_day_charge = null;
          props.form.opsChartererContractsFinancialTerms.opsVoyage = null;
          props.form.opsChartererContractsFinancialTerms.per_ton_charge = null;
          props.form.opsChartererContractsFinancialTerms.bunker_provider = '';
          props.form.opsChartererContractsFinancialTerms.ops_cargo_type = null;
          props.form.opsChartererContractsFinancialTerms.loading_point = '';
          props.form.opsChartererContractsFinancialTerms.final_unloading_point = '';
          props.form.opsChartererContractsFinancialTerms.approximate_load_amount = null;

          
          // props.form.opsChartererContractsFinancialTerms.opsCargoTariff = null;
          // props.form.opsChartererContractsFinancialTerms.ops_cargo_tariff_id = null;
      }
}, { deep : true })

watch(() => props.form, (value) => {

  if(props?.formType == 'edit' && editInitiated.value != true) {

    if(vessels.value.length > 0) {
        editInitiated.value = true
      }
  }
}, {deep: true});


watch(() => props.form.opsChartererContractsLocalAgents[0].opsPort, (value) => {
  if(value) {
    props.form.opsChartererContractsLocalAgents[0].port_code = value?.code
  } else {
    props.form.opsChartererContractsLocalAgents[0].port_code = null
  }
}, { deep: true})

watch(() => props.form.opsVessel, (value) => {
  if(value) {
    props.form.ops_vessel_id = value?.id
    props.form.vessel_owner = value?.owner_name
  } else {
    props.form.ops_vessel_id = null;
    props.form.vessel_owner = ''
  }
}, { deep: true})


// watch(() => props.form.opsChartererContractsFinancialTerms.opsCargoTariff, (value) => {
//   if(value) {
//     props.form.opsChartererContractsFinancialTerms.ops_cargo_tariff_id = value?.id
//   } else {
//     props.form.opsChartererContractsFinancialTerms.ops_cargo_tariff_id = null
//   }
// }, { deep: true })

watch(() => props.form.opsChartererProfile, (value) => {
  if(value) {
    props.form.ops_charterer_profile_id = value?.id
    props.form.charterer_code = value?.owner_code
    
    if(props?.formType == 'edit' && editInitiated.value == true) {
      replaceThings(value)
    }
    
    if(props?.formType == 'create') {
      replaceThings(value)
    }

  } else {
    replaceThings(null)
  }
}, { deep: true})

function replaceThings(value) {

    props.form.charterer_code = value?.owner_code
    props.form.country = value?.country
    props.form.address = value?.address
    props.form.billing_address = value?.billing_address
    props.form.email = value?.email
    props.form.contact_no = value?.contact_no

    if(value?.opsChartererBankAccounts) {
      props.form.bank_name = value?.opsChartererBankAccounts[0]?.bank_name
      props.form.bank_branch_name = value?.opsChartererBankAccounts[0]?.bank_branch_name
      props.form.bank_account_no = value?.opsChartererBankAccounts[0]?.bank_account_no
      props.form.bank_account_name = value?.opsChartererBankAccounts[0]?.bank_account_name
      props.form.swift_code = value?.opsChartererBankAccounts[0]?.swift_code
      props.form.routing_no = value?.opsChartererBankAccounts[0]?.routing_no
      props.form.currency = value?.opsChartererBankAccounts[0]?.currency
    }
}


onMounted(() => {
  getCurrencies();
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