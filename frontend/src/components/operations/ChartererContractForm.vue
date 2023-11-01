<template>

    <div id="basic-info">
      <h4 class="text-md font-semibold">Basic Info</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Vessel Owner</span>
              <select v-model="form.contract_type" class="form-input">
                <option>Voyage Wise</option>
                <option>Day Wise</option>
              </select>
              <Error v-if="errors?.contract_type" :errors="errors.contract_type" />
          </label>


          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Select Vessel <span class="text-red-500">*</span></span>
              <v-select :options="vessels" placeholder="--Choose an option--" @search="fetchVessels"  v-model="form.ops_vessel_id" label="name" class="block form-input" :reduce="vessel=>vessel.id">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.ops_vessel_id"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
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
              <v-select :options="chartererProfiles" placeholder="--Choose an option--" @search="fetchCharterers"  v-model="form.ops_charterer_profile_id" label="name" class="block form-input" :reduce="vessel=>vessel.id">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.ops_charterer_profile_id"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
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
              <v-select :options="ports" placeholder="--Choose an option--" @search="fetchPorts"  v-model="form.chartererContractsAgents.port_code" label="name" class="block form-input" :reduce="vessel=>vessel.id">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.chartererContractsAgents.port_code"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
          </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Agent Name</span>
              <input type="text" v-model="form.chartererContractsAgents.agent_name" placeholder="Bank Name" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Billing Name </span>
              <input type="text" v-model="form.chartererContractsAgents.agent_billing_name" placeholder="Bank Branch" class="form-input" autocomplete="off" />
          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Billing Email</span>
            <input type="text" v-model="form.chartererContractsAgents.agent_billing_email" placeholder="Account No" class="form-input" autocomplete="off" />
        </label>
      </div>
    </div>

    <div id="contract-validity">
      <h4 class="text-md font-semibold mt-4">Contract Validity and Billing</h4>
      <div v-if="form.contract_type == 'Day Wise'" class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Credit Days</span>
              <input type="text" v-model="form.chartererContractsFinancialTerms.credit_days" placeholder="Credit Days" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Billing Cycle </span>
              <input type="text" v-model="form.chartererContractsFinancialTerms.billing_cycle" placeholder="Billing Cycle" class="form-input" autocomplete="off" />
          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Valid From</span>
            <input type="date" v-model="form.chartererContractsFinancialTerms.valid_from" placeholder="Valid From" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Valid Till</span>
            <input type="text" v-model="form.chartererContractsFinancialTerms.valid_till" placeholder="Account No" class="form-input" autocomplete="off" />
        </label>
      </div>
      <div v-if="form.contract_type == 'Voyage Wise'" class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Voyage</span>
              <input type="text" v-model="form.chartererContractsFinancialTerms.ops_voyage_id" placeholder="Credit Days" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Cargo Type </span>
              <v-select :options="cargoTypes" placeholder="--Choose an option--" @search="fetchCargoTypes"  v-model="form.chartererContractsFinancialTerms.ops_cargo_type_id" label="cargo_type" class="block form-input" :reduce="cargoType=>cargoType.id">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.chartererContractsFinancialTerms.ops_cargo_type_id"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Loading Point</span>
            <input type="date" v-model="form.chartererContractsFinancialTerms.loading_point" placeholder="Valid From" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Final Unloading Point</span>
            <input type="text" v-model="form.chartererContractsFinancialTerms.final_unloading_point" placeholder="Account No" class="form-input" autocomplete="off" />
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
          <input type="text" v-model="form.chartererContractsFinancialTerms.approximate_load_amount" placeholder="Per Day Charge" class="form-input" autocomplete="off" />
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
              <input type="text" v-model="form.chartererContractsFinancialTerms.per_day_charge" placeholder="Per Day Charge" class="form-input" autocomplete="off" />
        </label>
        <label v-if="form.contract_type == 'Voyage Wise'" class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Per MT Charge</span>
              <input type="text" v-model="form.chartererContractsFinancialTerms.per_ton_charge" placeholder="Per MT Charge" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Cleaning Fee </span>
              <input type="text" v-model="form.chartererContractsFinancialTerms.cleaning_fee" placeholder="Cleaning Fee" class="form-input" autocomplete="off" />
          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Cancellation Fee <small>(%)</small></span>
            <input type="text" v-model="form.chartererContractsFinancialTerms.cancellation_fee" placeholder="Cancellation Fee" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Others Fee</span>
            <input type="text" v-model="form.chartererContractsFinancialTerms.others_fee" placeholder="Others Fee" class="form-input" autocomplete="off" />
        </label>
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300 font-semibold">Bunker Provider</span>
        </label>
        <label class="block w-full mt-2 text-sm">
              <input type="radio" v-model="form.chartererContractsFinancialTerms.bunker_provider" name="bunker_provider" value="Ship Owner" />
              <span class="text-gray-700 dark:text-gray-300 ml-2">By Ship Owner </span>
          </label>
        <label class="block w-full mt-2 text-sm">
            <input type="radio" v-model="form.chartererContractsFinancialTerms.bunker_provider" name="bunker_provider" value="By Charterer" />
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

const store = useStore();
const editInitiated = ref(false);
const { getCurrencies, currencies } = useBusinessInfo();
const { vessel, vessels, searchVessels, showVessel } = useVessel();
const { ports, searchPorts } = usePort();
const { searchChartererProfiles, chartererProfiles } = useChartererProfile();
const { cargoTypes, searchCargoTypes } = useCargoType();

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

watch(() => props.form, (value) => {

  if(props?.formType == 'edit' && editInitiated.value != true) {

  //   vessels.value = [props?.form?.opsVessel]

  //   if(vessels.value.length > 0) {
  //       console.log("Changing editInitatedValue ")
  //       editInitiated.value = true
  //     }
  }
}, {deep: true});


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