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
              <span class="text-gray-700 dark:text-gray-300">Contract Name</span>
              <input type="text" v-model="form.contract_name" placeholder="Contract Name" class="form-input" autocomplete="off" />
            <Error v-if="errors?.contract_name" :errors="errors.contract_name" />
          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Contract Type</span>
              <select :disabled="formType=='edit'" v-model="form.contract_type" class="form-input">
                <option>Voyage Wise</option>
                <option>Day Wise</option>
              </select>
              <Error v-if="errors?.contract_type" :errors="errors.contract_type" />
          </label>


          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Select Vessel <span class="text-red-500">*</span></span>
              <v-select :options="vessels" placeholder="--Choose an option--" @search="fetchVessels"  v-model="form.opsVessel" label="name" class="block form-input">
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
              <span class="text-gray-700 dark:text-gray-300">Vessel Owner</span>
              <input type="text" readonly v-model="form.vessel_owner" placeholder="Vessel Owner" class="form-input bg-gray-100" autocomplete="off" />
            <Error v-if="errors?.vessel_owner" :errors="errors.vessel_owner" />
          </label>

          
          
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Select Charterer <span class="text-red-500">*</span></span>
              <v-select :options="chartererProfiles" placeholder="--Choose an option--" @search="fetchCharterers"  v-model="form.opsChartererProfile" label="name" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsChartererProfile"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
              <input type="hidden" v-mode="form.ops_charterer_profile_id" />

          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Charterer Code</span>
              <input type="text" v-model="form.charterer_code" placeholder="Charterer Code" class="form-input bg-gray-100" readonly autocomplete="off" />
            <Error v-if="errors?.charterer_code" :errors="errors.charterer_code" />
          </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Country</span>
              <input type="text" v-model="form.country" placeholder="Country" class="form-input" autocomplete="off" />
            <Error v-if="errors?.country" :errors="errors.country" />
          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Address</span>
            <input type="text" v-model="form.address" placeholder="Address" class="form-input" autocomplete="off" />
          <Error v-if="errors?.address" :errors="errors.address" />
        </label>
        
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Billing Address</span>
              <input type="text" v-model="form.billing_address" placeholder="Billing Address" class="form-input" autocomplete="off" />
            <Error v-if="errors?.billing_address" :errors="errors.billing_address" />
          </label>
        
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Email</span>
              <input type="text" v-model="form.email" placeholder="Email" class="form-input" autocomplete="off" />
            <Error v-if="errors?.email" :errors="errors.email" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Contact No.</span>
            <input type="text" v-model="form.contact_no" placeholder="Contact No." class="form-input" autocomplete="off" />
          <Error v-if="errors?.contact_no" :errors="errors.contact_no" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Attachment </span>
              <input type="file" @change="attachFile" placeholder="Billing Email" class="form-input" autocomplete="off" />
          </label>
      </div>
    </div>
    
    <div id="bank-account-info">
      <h4 class="text-md font-semibold mt-4">Bank Account Info</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Bank Name</span>
              <input type="text" v-model="form.bank_name" placeholder="Bank Name" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Bank Branch </span>
              <input type="text" v-model="form.bank_branch_name" placeholder="Bank Branch" class="form-input" autocomplete="off" />
          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Account No</span>
            <input type="text" v-model="form.bank_account_no" placeholder="Account No" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Account Name</span>
              <input type="text" v-model="form.bank_account_name" placeholder="Account Name" class="form-input" autocomplete="off" />
          </label>
          
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Swift Code</span>
              <input type="text" v-model="form.swift_code" placeholder="Swift Code" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Routing No</span>
            <input type="text" v-model="form.routing_no" placeholder="Routing No" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Currency</span>
                <select v-model="form.currency" class="form-input">
                  <option value="">Select Currency</option>
                  <option v-for="currency in currencies">{{ currency }}</option>
                </select>
                <Error v-if="errors?.model_name" :errors="errors.model_name" />
        </label>
        <label class="block w-full mt-2 text-sm"></label>
      </div>
    </div>

    <div id="local-agent-info">
      <h4 class="text-md font-semibold mt-4">Local Agent Info</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Port <span class="text-red-500">*</span></span>
              <v-select :options="ports" placeholder="--Choose an option--" @search="fetchPorts"  v-model="form.opsChartererContractsLocalAgents[0].port_code" label="name" class="block form-input" :reduce="vessel=>vessel.id">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsChartererContractsLocalAgents[0].port_code"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
          </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Agent Name</span>
              <input type="text" v-model="form.opsChartererContractsLocalAgents[0].agent_name" placeholder="Agent Name" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Billing Name </span>
              <input type="text" v-model="form.opsChartererContractsLocalAgents[0].agent_billing_name" placeholder="Billing Name" class="form-input" autocomplete="off" />
          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Billing Email</span>
            <input type="text" v-model="form.opsChartererContractsLocalAgents[0].agent_billing_email" placeholder="Billing Email" class="form-input" autocomplete="off" />
        </label>
      </div>
    </div>

    <div id="contract-validity">
      <h4 class="text-md font-semibold mt-4">Contract Validity and Billing</h4>
      <div v-if="form.contract_type == 'Day Wise'" class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Credit Days</span>
              <input type="text" v-model="form.opsChartererContractsFinancialTerms.credit_days" placeholder="Credit Days" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Billing Cycle </span>
              <input type="text" v-model="form.opsChartererContractsFinancialTerms.billing_cycle" placeholder="Billing Cycle" class="form-input" autocomplete="off" />
          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Valid From</span>
            <input type="date" v-model="form.opsChartererContractsFinancialTerms.valid_from" placeholder="Valid From" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Valid Till</span>
            <input type="date" v-model="form.opsChartererContractsFinancialTerms.valid_till" placeholder="Valid Till" class="form-input" autocomplete="off" />
        </label>
      </div>
      <div v-if="form.contract_type == 'Voyage Wise'" class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Voyage</span>
              <v-select :options="voyages" placeholder="--Choose an option--" @search="fetchVoyages"  v-model="form.opsChartererContractsFinancialTerms.opsVoyage" label="cargo_type" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsChartererContractsFinancialTerms.opsVoyage"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
              <input type="hidden" v-model="form.opsChartererContractsFinancialTerms.ops_voyage_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Cargo Type </span>
              <v-select :options="cargoTypes" placeholder="--Choose an option--" @search="fetchCargoTypes"  v-model="form.opsChartererContractsFinancialTerms.opsCargoType" label="cargo_type" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsChartererContractsFinancialTerms.opsCargoType"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
              <input type="hidden" v-model="form.opsChartererContractsFinancialTerms.ops_cargo_type_id" />
          </label>
        
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Loading Point <span class="text-red-500">*</span></span>
              <v-select :options="ports" placeholder="--Choose an option--" @search="fetchPorts"  v-model="form.opsChartererContractsFinancialTerms.loading_point" label="name" class="block form-input" :reduce="vessel=>vessel.id">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsChartererContractsFinancialTerms.loading_point"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Unloading Point <span class="text-red-500">*</span></span>
              <v-select :options="ports" placeholder="--Choose an option--" @search="fetchPorts"  v-model="form.opsChartererContractsFinancialTerms.final_unloading_point" label="name" class="block form-input" :reduce="vessel=>vessel.id">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsChartererContractsFinancialTerms.final_unloading_point"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
          </label>
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300"> Status</span>
                <select v-model="form.status" class="form-input">
                  <option value="">Select Status</option>
				          <option>Active</option>
				          <option>Inactive</option>
				        </select>
        </label>
        <label v-if="form.contract_type == 'Day Wise'" class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Approximate Load Amount</span>
          <input type="text" v-model="form.opsChartererContractsFinancialTerms.approximate_load_amount" placeholder="Approximate Load Amount" class="form-input" autocomplete="off" />
        </label>
        <label v-else class="block w-full mt-2 text-sm"></label>
        <label class="block w-full mt-2 text-sm"></label>
        <label class="block w-full mt-2 text-sm"></label>
      </div>
    </div>

    <div id="rates-fess">
      <h4 class="text-md font-semibold mt-4">Rates and Fees</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label v-if="form.contract_type == 'Day Wise'" class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Per Day Charge</span>
              <input type="text" v-model="form.opsChartererContractsFinancialTerms.per_day_charge" placeholder="Per Day Charge" class="form-input" autocomplete="off" />
        </label>
        <label v-if="form.contract_type == 'Voyage Wise'" class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Per MT Charge</span>
              <input type="text" v-model="form.opsChartererContractsFinancialTerms.per_ton_charge" placeholder="Per MT Charge" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Cleaning Fee </span>
              <input type="text" v-model="form.opsChartererContractsFinancialTerms.cleaning_fee" placeholder="Cleaning Fee" class="form-input" autocomplete="off" />
          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Cancellation Fee <small>(%)</small></span>
            <input type="text" v-model="form.opsChartererContractsFinancialTerms.cancellation_fee" placeholder="Cancellation Fee" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Others Fee</span>
            <input type="text" v-model="form.opsChartererContractsFinancialTerms.others_fee" placeholder="Others Fee" class="form-input" autocomplete="off" />
        </label>
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300 font-semibold">Bunker Provider</span>
        </label>
        <label class="block w-full mt-2 text-sm">
              <input type="radio" v-model="form.opsChartererContractsFinancialTerms.bunker_provider" name="bunker_provider" value="Ship Owner" />
              <span class="text-gray-700 dark:text-gray-300 ml-2">By Ship Owner </span>
          </label>
        <label class="block w-full mt-2 text-sm">
            <input type="radio" v-model="form.opsChartererContractsFinancialTerms.bunker_provider" name="bunker_provider" value="By Charterer" />
            <span class="text-gray-700 dark:text-gray-300 ml-2">By Charterer</span>
        </label>
        <label class="block w-full mt-2 text-sm"></label>
      </div>
    </div>
</template>
<script setup>
import { ref, watch, onMounted } from "vue";
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import {useStore} from "vuex";
import useBusinessInfo from "../../composables/useBusinessInfo"
import useVessel from "../../composables/operations/useVessel";
import usePort from "../../composables/operations/usePort";
import useChartererProfile from "../../composables/operations/useChartererProfile";
import useCargoType from "../../composables/operations/useCargoType";
import useVoyage from "../../composables/operations/useVoyage";

const store = useStore();
const editInitiated = ref(false);
const { getCurrencies, currencies } = useBusinessInfo();
const { vessel, vessels, searchVessels, showVessel } = useVessel();
const { ports, searchPorts } = usePort();
const { searchChartererProfiles, chartererProfiles } = useChartererProfile();
const { cargoTypes, searchCargoTypes } = useCargoType();
const { voyages, searchVoyages } = useVoyage();

const props = defineProps({
    form: {
        required: false,
        default: {},
    },
    errors: { type: [Object, Array], required: false },
    formType: { type: String, required : false }
});

function fetchVessels(search, loading) {
      loading(true);
      searchVessels(search, props.form.business_unit, loading);
}

function fetchVoyages(search, loading) {
  if(props.form.ops_vessel_id) {
    loading(true);
    searchVoyages(search, props.form.business_unit, loading)
  }
}

function fetchPorts(search, loading) {
      loading(true);
      searchPorts(search, loading)
}

function fetchCharterers(search, loading) {
      loading(true);
      searchChartererProfiles(search, loading)
}

function fetchCargoTypes(search, loading) {
      loading(true);
      searchCargoTypes(search, loading)
}

function attachFile(e) {
    let fileData = e.target.files[0];
    props.form.attachment = fileData;
}

watch(() => props.form.business_unit, (value) => {
  if(props?.formType != 'edit') {
    props.form.opsVessel = null;
    vessels.value = []
  }
  
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
      }
}, { deep : true })

watch(() => props.form, (value) => {

  if(props?.formType == 'edit' && editInitiated.value != true) {

    vessels.value = [{...props?.form?.opsVessel}];

    if(vessels.value.length > 0) {
        editInitiated.value = true
      }
  }
}, {deep: true});

watch(() => props.form.opsVessel, (value) => {
  if(value) {
    props.form.ops_vessel_id = value?.id
    props.form.vessel_owner = value?.owner_name
  } else {
    props.form.ops_vessel_id = null;
    props.form.vessel_owner = ''
  }
}, { deep: true})

watch(() => props.form.opsChartererContractsFinancialTerms.opsCargoType, (value) => {
  if(value) {
    props.form.opsChartererContractsFinancialTerms.ops_cargo_type_id = value?.id
  } else {
    props.form.opsChartererContractsFinancialTerms.ops_cargo_type_id = null
  }
}, { deep: true })

watch(() => props.form.opsChartererContractsFinancialTerms.opsVoyage, (value) => {
  if(value) {
    props.form.opsChartererContractsFinancialTerms.ops_voyage_id = value?.id
  } else {
    props.form.opsChartererContractsFinancialTerms.ops_voyage_id = null
  }
}, { deep: true })

watch(() => props.form.opsChartererProfile, (value) => {
  if(value) {
    props.form.ops_charterer_profile_id = value?.id
    props.form.charterer_code = value?.owner_code
    
    if(props?.formType == 'edit' && editInitiated.value == true) {
      replaceThings()
    } else if(props?.formType == 'create') {
      replaceThings()
    }

  }
}, { deep: true})

function replaceThings() {
    props.form.charterer_code = value?.owner_code
    props.form.country = value?.country
    props.form.address = value?.address
    props.form.billing_address = value?.billing_address
    props.form.email = value?.email
    props.form.contact_no = value?.contact_no
    props.form.bank_name = value?.opsChartererBankAccounts[0]?.bank_name
    props.form.bank_branch_name = value?.opsChartererBankAccounts[0]?.bank_branch_name
    props.form.bank_account_no = value?.opsChartererBankAccounts[0]?.bank_account_no
    props.form.bank_account_name = value?.opsChartererBankAccounts[0]?.bank_account_name
    props.form.swift_code = value?.opsChartererBankAccounts[0]?.swift_code
    props.form.routing_no = value?.opsChartererBankAccounts[0]?.routing_no
    props.form.currency = value?.opsChartererBankAccounts[0]?.currency
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
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
}
</style>