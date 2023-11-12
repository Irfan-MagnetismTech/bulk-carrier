<template>
    <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Tariff Name <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.tariff_name" placeholder="Tariff Name" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.tariff_name" :errors="errors.tariff_name" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Vessel <span class="text-red-500">*</span></span>
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
          <Error v-if="errors?.description" :errors="errors.description" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300"> Loading Point <span class="text-red-500">*</span></span>
            <v-select :options="ports" placeholder="--Choose an option--" @search="fetchPorts"  v-model="form.loading_point" label="name" class="block form-input" :reduce="port=>port.code">
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!form.loading_point"
                        v-bind="attributes"
                        v-on="events"
                        />
                </template>
            </v-select>
          <Error v-if="errors?.cargo_type" :errors="errors.cargo_type" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Unloading Point <span class="text-red-500">*</span></span>
            <v-select :options="ports" placeholder="--Choose an option--" @search="fetchPorts"  v-model="form.unloading_point" label="name" class="block form-input" :reduce="port=>port.code">
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!form.unloading_point"
                        v-bind="attributes"
                        v-on="events"
                        />
                </template>
            </v-select>
          <Error v-if="errors?.cargo_type" :errors="errors.cargo_type" />
        </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Cargo Type <span class="text-red-500">*</span></span>
              <v-select :options="cargoTypes" placeholder="--Choose an option--" @search="fetchCargoTypes"  v-model="form.ops_cargo_type_id" label="cargo_type" class="block form-input" :reduce="cargoType=>cargoType.id">
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!form.ops_cargo_type_id"
                        v-bind="attributes"
                        v-on="events"
                        />
                </template>
            </v-select>
            <Error v-if="errors?.cargo_type" :errors="errors.cargo_type" />
          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Currency <span class="text-red-500">*</span></span>
              <select name="" id="" class="form-input" v-model="form.currency">
                <option value="USD">USD</option>
                <option value="BDT">BDT</option>
              </select>
            <Error v-if="errors?.currency" :errors="errors.currency" />
          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></span>
              <select name="" id="" class="form-input" v-model="form.status">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
            <Error v-if="errors?.status" :errors="errors.status" />
          </label>
          <label class="block w-full mt-2 text-sm"></label>
    </div>

    <div class="mt-3 md:mt-8">
      <div id="customDataTable">
        <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
          
          <table class="w-full whitespace-no-wrap" >
              <thead v-once>
                <tr class="w-full">
                  <th>Particulars <span class="text-red-500">*</span></th>
                  <th>Unit <span class="text-red-500">*</span></th>
                  <th>Jan</th>
                  <th>Feb</th>
                  <th>Mar</th>
                  <th>Apr</th>
                  <th>May</th>
                  <th>Jun</th>
                  <th>Jul</th>
                  <th>Aug</th>
                  <th>Sep</th>
                  <th>Oct</th>
                  <th>Nov</th>
                  <th>Dec</th>
                  <th>
                    <button type="button" @click="addItem()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                      </svg>
                    </button>
                  </th>
                </tr>
              </thead>

              <tbody>
                  <tr v-for="(cargoTariff, index) in form.opsCargoTariffLines">
                    <td>
                      <input type="text" v-model="form.opsCargoTariffLines[index].particular" placeholder="Particular" class="form-input" required autocomplete="off" />
                    </td>
                    <td>
                      <input type="text" v-model="form.opsCargoTariffLines[index].unit" placeholder="Unit" class="form-input" required autocomplete="off" />
                    </td>
                    <td>
                      <input type="number" step="0.001" v-model="form.opsCargoTariffLines[index].jan" class="form-input" autocomplete="off" />
                    </td>
                    <td>
                      <input type="number" step="0.001" v-model="form.opsCargoTariffLines[index].feb" class="form-input" autocomplete="off" />
                    </td>
                    <td>
                      <input type="number" step="0.001" v-model="form.opsCargoTariffLines[index].mar" class="form-input" autocomplete="off" />
                    </td>
                    <td>
                      <input type="number" step="0.001" v-model="form.opsCargoTariffLines[index].apr" class="form-input" autocomplete="off" />
                    </td>
                    <td>
                      <input type="number" step="0.001" v-model="form.opsCargoTariffLines[index].may" class="form-input" autocomplete="off" />
                    </td>
                    <td>
                      <input type="number" step="0.001" v-model="form.opsCargoTariffLines[index].jun" class="form-input" autocomplete="off" />
                    </td>
                    <td>
                      <input type="number" step="0.001" v-model="form.opsCargoTariffLines[index].jul" class="form-input" autocomplete="off" />
                    </td>
                    <td>
                      <input type="number" step="0.001" v-model="form.opsCargoTariffLines[index].aug" class="form-input" autocomplete="off" />
                    </td>
                    <td>
                      <input type="number" step="0.001" v-model="form.opsCargoTariffLines[index].sep" class="form-input" autocomplete="off" />
                    </td>
                    <td>
                      <input type="number" step="0.001" v-model="form.opsCargoTariffLines[index].oct" class="form-input" autocomplete="off" />
                    </td>
                    <td>
                      <input type="number" step="0.001" v-model="form.opsCargoTariffLines[index].nov" class="form-input" autocomplete="off" />
                    </td>
                    <td>
                      <input type="number" step="0.001" v-model="form.opsCargoTariffLines[index].dec" class="form-input" autocomplete="off" />
                    </td>
                    <td>
                      <button type="button" @click="removeItem(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                      </button> 
                    </td>
                  </tr>
              </tbody>
          </table>

        </div>
      </div>
    </div>

</template>
<script setup>
import { ref, watch } from 'vue';
import Error from "../../Error.vue";
import usePort from '../../../composables/operations/usePort';
import useVessel from '../../../composables/operations/useVessel';
import useCargoType from '../../../composables/operations/useCargoType';
import BusinessUnitInput from "../../input/BusinessUnitInput.vue";

const { ports, searchPorts } = usePort();
const { vessels, searchVessels } = useVessel();
const { cargoTypes, searchCargoTypes } = useCargoType();

const props = defineProps({
    form: {
        required: false,
        default: {}
    },
    errors: { type: [Object, Array], required: false },
    cargoTariffLineObject: { type: Object },
    formType: { type: String, required : false }
});

const editInitiated = ref(false);

function fetchVessels(search, loading) {
      loading(true);
      searchVessels(search, props.form.business_unit, loading);
}

function fetchPorts(search, loading) {
      loading(true);
      searchPorts(search, loading)
}

function fetchCargoTypes(search, loading) {
      loading(true);
      searchCargoTypes(search, loading)
}

function addItem() {
  props.form.opsCargoTariffLines.push(props.cargoTariffLineObject);
}

function removeItem(index){
  props.form.opsCargoTariffLines.splice(index, 1);
}

watch(() => props.form, (value) => {

    if(props?.formType == 'edit' && editInitiated.value != true) {

      cargoTypes.value = [props?.form?.opsCargoType]
      vessels.value = [props?.form?.opsVessel]

      if(cargoTypes.value.length> 0 && vessels.value.length > 0) {
        editInitiated.value = true
      }
    }
  }, {deep: true});

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

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    /* display: none; <- Crashes Chrome on hover */
    -webkit-appearance: none;
    margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
}

input[type=number] {
    -moz-appearance:textfield; /* Firefox */
}
</style>