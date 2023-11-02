<template>

    <div id="basic-info">
      <h4 class="text-md font-semibold">Basic Info</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Note Type</span>
              <select v-model="form.note_type" class="form-input">
                <option>Delivery</option>
                <option>Re-delivery</option>
              </select>
              <Error v-if="errors?.note_type" :errors="errors.note_type" />
          </label>


          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Effective Date <span class="text-red-500">*</span></span>
              <input type="date" v-model="form.effective_date" placeholder="Effective Date" class="form-input bg-gray-100" autocomplete="off" />
              <Error v-if="errors?.effective_date" :errors="errors.effective_date" />

            </label>

          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Exchange Rate</span>
              <input type="number" step="0.001" v-model="form.exchange_rate" placeholder="Exchange Rate" class="form-input bg-gray-100" autocomplete="off" />
            <Error v-if="errors?.exchange_rate" :errors="errors.exchange_rate" />
          </label>

          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Currency</span>
                <select v-model="form.currency" class="form-input">
                  <option value="">Select Currency</option>
                  <option v-for="currency in currencies">{{ currency }}</option>
                </select>
                <Error v-if="errors?.model_name" :errors="errors.model_name" />
        </label>
          
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
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
              <span class="text-gray-700 dark:text-gray-300">Vessel Code</span>
              <input type="text" v-model="form.charterer_code" placeholder="Vessel Code" class="form-input bg-gray-100" readonly autocomplete="off" />
            <Error v-if="errors?.charterer_code" :errors="errors.charterer_code" />
          </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Vessel Owner Name</span>
              <input type="text" v-model="form.country" placeholder="Vessel Owner Name" class="form-input" autocomplete="off" />
            <Error v-if="errors?.country" :errors="errors.country" />
          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Vessel Capacity</span>
            <input type="text" v-model="form.address" placeholder="Vessel Capacity" class="form-input" autocomplete="off" />
          <Error v-if="errors?.address" :errors="errors.address" />
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
              <input type="text" v-model="form.email" placeholder="Charterer Code" class="form-input" autocomplete="off" />
            <Error v-if="errors?.email" :errors="errors.email" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Charterer Email</span>
              <input type="text" v-model="form.email" placeholder="Charterer Email" class="form-input" autocomplete="off" />
            <Error v-if="errors?.email" :errors="errors.email" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Contact No.</span>
            <input type="text" v-model="form.contact_no" placeholder="Contact No." class="form-input" autocomplete="off" />
          <Error v-if="errors?.contact_no" :errors="errors.contact_no" />
        </label>
      </div>
    </div>

    <div id="bunker-info" class="mt-5">
      <h4 class="text-md font-semibold uppercase mb-2">Bunker Information</h4>
        <table class="w-full whitespace-no-wrap" >
          <thead v-once>
            <tr class="w-full">
              <th>SL</th>
              <th class="w-72">Bunker Name</th>
              <th>Unit</th>
              <th>Quantity</th>
              <th>Rate</th>
              <th>Amount <small>(USD)</small></th>
              <th>Amount <small>(BDT)</small></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(certificate, index) in form.opsBunkers">
              <td>
                {{ index+1 }}
              </td>
              <td>
                <v-select :options="materials" placeholder="--Choose an option--" @search="fetchBunker"  v-model="form.opsBunkers[index]" label="name" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsBunkers[index]"
                          v-bind="attributes"
                          v-on="events"
                          :reaonly="true"
                          />
                  </template>
              </v-select>
              </td>
              <td>
                <span class="show-block !justify-center !bg-gray-100" v-if="form.opsBunkers[index]?.unit">{{ form.opsBunkers[index]?.unit }}</span>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" v-model="form.opsBunkers[index].opening_balance" readonly placeholder="Present Stock" class="form-input text-right bg-gray-100" autocomplete="off" :disabled="formType=='edit'"/>
                  <Error v-if="errors?.opsBunkers[index]?.opening_balance" :errors="errors.opsBunkers[index]?.opening_balance" />
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" v-model="form.opsBunkers[index].quantity" placeholder="Stock In" class="form-input text-right" autocomplete="off" :disabled="formType=='edit'"/>
                  <Error v-if="errors?.opsBunkers[index]?.quantity" :errors="errors.opsBunkers[index]?.quantity" />
                </label>
              </td>
            </tr>
          </tbody>
        </table>
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