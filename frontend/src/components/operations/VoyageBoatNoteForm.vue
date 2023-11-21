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
              <span class="text-gray-700 dark:text-gray-300">Voyage </span>
              <v-select :options="voyages" placeholder="--Choose an option--" @search="fetchVoyages"  v-model="form.opsVoyage" label="voyage_sequence" class="block form-input">
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
              <input type="text" readonly v-model.trim="form.vessel_name" placeholder="Vessel" class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Draft</span>
              <input type="number" step="0.001" v-model.trim="form.vessel_draft" placeholder="Draft" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Density</span>
              <input type="number" step="0.001" v-model.trim="form.water_density" placeholder="Density" class="form-input" autocomplete="off" />
        </label>
    </div>

    <div id="sectors" class="mt-5" v-if="form.opsVoyageBoatNoteLines?.length > 0">
      <h4 class="text-md font-semibold my-3">Sector Info</h4>

      <table class="w-full whitespace-no-wrap" >
          <thead v-once>
            <tr class="w-full">
              <th>SL</th>
              <th class="w-36">Type</th>
              <th class="w-72">Loading Point</th>
              <th>Unloading Point</th>
              <th>Quantity</th>
              <th>Attachment</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(sector, index) in form.opsVoyageBoatNoteLines">
              <td>
                {{ index+1 }}
              </td>
              <td>
                <span class="show-block !justify-center !bg-gray-100" v-if="form.opsVoyageBoatNoteLines[index]?.type">{{ form.opsVoyageBoatNoteLines[index]?.type }}</span>
              </td>
              <td>
                <span class="show-block !justify-center !bg-gray-100" v-if="form.opsVoyageBoatNoteLines[index]?.loading_point">{{ form.opsVoyageBoatNoteLines[index]?.loading_point }}</span>
              </td>
              <td>
                <span class="show-block !justify-center !bg-gray-100" v-if="form.opsVoyageBoatNoteLines[index]?.unloading_point">{{ form.opsVoyageBoatNoteLines[index]?.unloading_point }}</span>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsVoyageBoatNoteLines[index].quantity" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <Error v-if="errors?.opsVoyageBoatNoteLines[index]?.quantity" :errors="errors.opsVoyageBoatNoteLines[index]?.quantity" />
                </label>
              </td>
              <td>
                <input type="file" @change="attachFile($event, index)" class="form-input text-right" autocomplete="off"/>
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

const editInitiated = ref(false);

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
    props.form.opsVoyageBoatNoteLines = [
    ...value?.value?.opsVoyageSectors.map((sector) => ({ ...sector, type: 'Boat Note' })),
    ...value?.value?.opsVoyageSectors.map((sector) => ({ ...sector, type: 'Final Survey' })),
    ...value?.value?.opsVoyageSectors.map((sector) => ({ ...sector, type: 'Receipt Copy' }))
];



// return [
//         { ...sector, type: 'Boat Note' },
//         { ...sector, type: 'Final Survey' },
//         { ...sector, type: 'Receipt Copy' },
//     ];

  }
}, { deep: true })


watch(() => props.form, (value) => {

  if(props?.formType == 'edit' && editInitiated.value != true) {

  
    if(vessels.value.length > 0) {
        editInitiated.value = true
      }
  }
}, {deep: true});

function attachFile(event, index) {
    let fileData = event.target.files[0];
    props.form.opsVoyageBoatNoteLines[index].attachment = fileData;
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