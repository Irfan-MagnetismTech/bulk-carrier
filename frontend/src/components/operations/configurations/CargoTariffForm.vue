<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Tariff Name <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.tariff_name" placeholder="Tariff Name" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.tariff_name" :errors="errors.tariff_name" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Select Vessel <span class="text-red-500">*</span></span>
            <v-select :options="vessels" placeholder="--Choose an option--" @search="fetchVessels"  v-model="form.ops_vessel_id" label="name" class="block form-input">
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
            <v-select :options="vessels" placeholder="--Choose an option--" @search="fetchPorts"  v-model="form.loading_point" label="name" class="block form-input">
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
            <v-select :options="vessels" placeholder="--Choose an option--" @search="fetchPorts"  v-model="form.unloading_point" label="name" class="block form-input">
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
              <v-select :options="vessels" placeholder="--Choose an option--" @search="fetchCargoTypes"  v-model="form.ops_cargo_type_id" label="name" class="block form-input">
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
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            <Error v-if="errors?.currency" :errors="errors.currency" />
          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></span>
              <select name="" id="" class="form-input" v-model="form.status">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
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
                  <th>Particulars</th>
                  <th>Unit</th>
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
                    <button class="flex border-none justify-center items-center w-full bg-green-500">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                      </svg>
                    </button>
                  </th>
                </tr>
              </thead>
          </table>

        </div>
      </div>
    </div>

</template>
<script setup>
import Error from "../../Error.vue";
import useCargoType from '../../../composables/operations/useCargoType';
const { cargoTypes, searchCargoTypes, errors } = useCargoType();


const props = defineProps({
    form: {
        required: false,
        default: {}
    },
    errors: { type: [Object, Array], required: false },
});

function fetchVessels(search, loading) {
      loading(true);
      // searchWarehouse(search, loading)
}

function fetchPorts(search, loading) {
      loading(true);
      // searchWarehouse(search, loading)
}

function fetchCargoTypes(search, loading) {
      loading(true);
      searchCargoTypes(search, loading)
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
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
}
</style>