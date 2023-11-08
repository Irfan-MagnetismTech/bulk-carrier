<template>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>

    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Note Type</span>
      </label>
      <label class="block w-full mt-2 text-sm">
            <input type="radio" v-model="form.type" name="type" value="Boat Note" />
            <span class="text-gray-700 dark:text-gray-300 ml-2">Boat Note </span>
        </label>
      <label class="block w-full mt-2 text-sm">
          <input type="radio" v-model="form.type" name="type" value="Final Survey" />
          <span class="text-gray-700 dark:text-gray-300 ml-2">Final Survey</span>
      </label>
      <label class="block w-full mt-2 text-sm">
          <input type="radio" v-model="form.type" name="type" value="Receipt Copy" />
          <span class="text-gray-700 dark:text-gray-300 ml-2">Receipt Copy</span>
      </label>
    </div>

    <h4 class="text-md font-semibold mt-3">Basic Info</h4>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Voyage </span>
              <v-select :options="voyages" placeholder="--Choose an option--" @search="fetchVoyages"  v-model="form.opsVoyage" label="tariff_name" class="block form-input">
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
              <span class="text-gray-700 dark:text-gray-300">Vessel</span>
              <input type="number" v-model="form.vessel_name" placeholder="Vessel" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Draft</span>
              <input type="number" v-model="form.vessel_draft" placeholder="Draft" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Density</span>
              <input type="number" v-model="form.water_density" placeholder="Density" class="form-input" autocomplete="off" />
        </label>
    </div>

    <div id="sectors">
      <h4 class="text-md font-semibold mt-3">Sector Info</h4>
      
    </div>
    
</template>
<script setup>
import { ref, watch, onMounted } from "vue";
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import useBusinessInfo from "../../composables/useBusinessInfo";
import useVoyage from "../../composables/operations/useVoyage";

const editInitiated = ref(false);

const { voyages, searchVoyages } = useVoyage();
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
    props.form.opsVessel = null;
  }
  
}, { deep : true })


watch(() => props.form, (value) => {

  if(props?.formType == 'edit' && editInitiated.value != true) {

    vessels.value = [{...props?.form?.opsVessel}];
    ports.value = [
      
        { ...props?.form.opsChartererContractsLocalAgents[0].opsPort }
      

    ]

    if(vessels.value.length > 0) {
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
</style>