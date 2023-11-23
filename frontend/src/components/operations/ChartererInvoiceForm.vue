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
              <span class="text-gray-700 dark-disabled:text-gray-300">Select Charterer Owner</span>
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
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Select Contract</span>
              <v-select :options="chartererProfiles" placeholder="--Choose an option--" v-model="form.opsVoyage" label="voyage_sequence" class="block form-input">
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
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Vessel</span>
              <input type="text" readonly v-model.trim="form.vessel_name" placeholder="Vessel" class="form-input bg-gray-100" autocomplete="off" />
        </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Bill From</span>
              <input type="text" readonly v-model.trim="form.bill_from" placeholder="Vessel" class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Bill Till</span>
              <input type="text" readonly v-model.trim="form.bill_till" placeholder="Vessel" class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Total Days</span>
              <input type="text" readonly v-model.trim="form.total_days" placeholder="Vessel" class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Charge Per Day</span>
              <input type="text" readonly v-model.trim="form.vessel_name" placeholder="Vessel" class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Total Amount</span>
              <input type="text" readonly v-model.trim="form.vessel_name" placeholder="Vessel" class="form-input bg-gray-100" autocomplete="off" />
        </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Voyage</span>
              <input type="text" readonly v-model.trim="form.vessel_name" placeholder="Vessel" class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Cargo Type</span>
              <input type="text" readonly v-model.trim="form.vessel_name" placeholder="Vessel" class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Cargo Quantity</span>
              <input type="text" readonly v-model.trim="form.vessel_name" placeholder="Vessel" class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Initial Loading Point</span>
              <input type="text" readonly v-model.trim="form.vessel_name" placeholder="Vessel" class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Final Loading Point</span>
              <input type="text" readonly v-model.trim="form.vessel_name" placeholder="Vessel" class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Rate Per MT</span>
              <input type="text" readonly v-model.trim="form.vessel_name" placeholder="Vessel" class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Total Amount</span>
              <input type="text" readonly v-model.trim="form.vessel_name" placeholder="Vessel" class="form-input bg-gray-100" autocomplete="off" />
        </label>
    </div>


    <div id="sectors" class="mt-5">
      <h4 class="text-md font-semibold my-3">Other</h4>

      <table class="w-full whitespace-no-wrap" >
        <thead v-once>
            <tr class="w-full">
              <th>SL</th>
              <th class="">Particulars</th>
              <th class="">Currency</th>
              <th class="">Unit</th>
              <th>Quantity</th>
              <th>Rate</th>
              <th>Exchange Rate (To USD)</th>
              <th>Exchange Rate (To BDT)</th>
              <th>Amount USD</th>
              <th>Amount BDT</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(sector, index) in form.opsChartererInvoiceOthers">
              <td>
                {{ index+1 }}
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceOthers[index].quantity" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" />
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceOthers[index].quantity" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" />
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceOthers[index].quantity" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" />
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceOthers[index].quantity" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" />
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceOthers[index].quantity" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" />
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceOthers[index].quantity" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" />
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceOthers[index].quantity" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" />
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceOthers[index].quantity" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" />
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceOthers[index].quantity" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" />
                </label>
              </td>
            </tr>
          </tbody>
        </table>     
      
    </div>
    

    <div id="sectors" class="mt-5">
      <h4 class="text-md font-semibold my-3">Services Taken From Charterer</h4>

      <table class="w-full whitespace-no-wrap" >
          <thead v-once>
            <tr class="w-full">
              <th>SL</th>
              <th class="w-32">Particulars</th>
              <th class="w-56">Currency</th>
              <th class="w-56">Unit</th>
              <th>Quantity</th>
              <th>Rate</th>
              <th>Exchange Rate (To USD)</th>
              <th>Exchange Rate (To BDT)</th>
              <th>Amount USD</th>
              <th>Amount BDT</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(sector, index) in form.opsChartererInvoiceServices">
              <td>
                {{ index+1 }}
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceServices[index].quantity" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <Error v-if="errors?.opsChartererInvoiceServices[index]?.quantity" :errors="errors.opsChartererInvoiceServices[index]?.quantity" />
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceServices[index].quantity" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <Error v-if="errors?.opsChartererInvoiceServices[index]?.quantity" :errors="errors.opsChartererInvoiceServices[index]?.quantity" />
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceServices[index].quantity" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <Error v-if="errors?.opsChartererInvoiceServices[index]?.quantity" :errors="errors.opsChartererInvoiceServices[index]?.quantity" />
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceServices[index].quantity" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <Error v-if="errors?.opsChartererInvoiceServices[index]?.quantity" :errors="errors.opsChartererInvoiceServices[index]?.quantity" />
                </label>
              </td>

              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceServices[index].quantity" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <Error v-if="errors?.opsChartererInvoiceServices[index]?.quantity" :errors="errors.opsChartererInvoiceServices[index]?.quantity" />
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceServices[index].quantity" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <Error v-if="errors?.opsChartererInvoiceServices[index]?.quantity" :errors="errors.opsChartererInvoiceServices[index]?.quantity" />
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceServices[index].quantity" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <Error v-if="errors?.opsChartererInvoiceServices[index]?.quantity" :errors="errors.opsChartererInvoiceServices[index]?.quantity" />
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceServices[index].quantity" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <Error v-if="errors?.opsChartererInvoiceServices[index]?.quantity" :errors="errors.opsChartererInvoiceServices[index]?.quantity" />
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceServices[index].quantity" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <Error v-if="errors?.opsChartererInvoiceServices[index]?.quantity" :errors="errors.opsChartererInvoiceServices[index]?.quantity" />
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
import useVoyage from "../../composables/operations/useVoyage";
import useVessel from "../../composables/operations/useVessel";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import useChartererProfile from "../../composables/operations/useChartererProfile";
import useChartererContract from "../../composables/operations/useChartererContract";

const editInitiated = ref(false);

const { searchChartererProfiles, chartererProfiles } = useChartererProfile();

const { voyage, voyages, showVoyage, searchVoyages } = useVoyage();
const { vessel, showVessel } = useVessel();
const props = defineProps({
    form: {
        required: false,
        default: {},
    },
    errors: { type: [Object, Array], required: false },
    formType: { type: String, required : false }
});


function fetchVoyages(searchParam, loading) {
  loading(true)
  searchVoyages(searchParam, props.form.business_unit, loading)
}

watch(() => props.form.business_unit, (value) => {
  if(props?.formType != 'edit') {
    props.form.opsVoyage = null;
    vessel.value = null;
    props.form.opsVoyageSectors = null;
    props.form.vessel_name = null;
    props.form.ops_voyage_id = null;
  }
  

}, { deep : true })

watch(() => props.form.opsVoyage, (value) => {
  
  if(value) {
    props.form.ops_voyage_id = value?.id
    props.form.ops_vessel_id = value?.ops_vessel_id
    showVoyage(value?.id)
    showVessel(value?.ops_vessel_id)
  }

}, { deep: true })

watch(() => vessel, (value) => {
  if(value?.value) {
    props.form.vessel_name = value?.value?.name
  }
}, { deep: true })

watch(() => voyage, (value) => {
  if(value?.value) {
    
  }
}, { deep: true })

onMounted(() => {
  searchChartererProfiles();
})
// function attachFile(event, index) {
//     let fileData = event.target.files[0];
//     props.form.opsVoyageBoatNoteLines[index].attachment = fileData;
// }

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