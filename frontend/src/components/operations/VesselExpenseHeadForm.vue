<script setup>
import Error from "../Error.vue";
import { watch, ref, onMounted } from 'vue';
// import useVesselExpenseHead from "../../composables/operations/useVesselExpenseHead";
import useExpenseHead from "../../composables/operations/useExpenseHead";
import useVessel from "../../composables/operations/useVessel";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import ErrorComponent from '../../components/utils/ErrorComponent.vue';

const { expenseHeads, searchExpenseHeads, showHead, isLoading, errors } = useExpenseHead();
const { vessel, vessels, getVesselList, showVessel } = useVessel();

const subheads = ref([]);

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  heads: { type: [Object, Array], required: true },
  errors: { type: [Object, Array], required: false },
});

function addSubHead() {
  let obj = {
    head_id: '',
    head_id_name: '',
    name: '',
  };
  props.form.sub_head.push(obj);
}

function removeSubHead(index){
  props.form.sub_head.splice(index, 1);
}

function getSubHead(headId, index) {
  // console.log(headId, index)
  subheads.value = props.heads[index].subheads
  // console.log(props.form.heads[index])
  // if(props.form.heads.includes(headId)) {
  //   subheads.value.map(({id})=>{ 
  //     props.form.heads.push(id)
  //   });
  // }
}

function fetchExpenseHeads(searchParam, loading) {
  searchExpenseHeads(searchParam, props.form.business_unit, loading)
}

function checkSubHead(headId, headIndex) {
  console.log()
  if(props.form.heads.includes(headId)) {
    props.heads[headIndex].subheads.map(({id})=>{ 
      props.form.heads.push(id)
    });
  }
}

watch(() => props.form.business_unit, (value) => {
  if (value) {
    getVesselList(props.form.business_unit);
    
  }
}, {deep: true});

onMounted(() => {
  fetchExpenseHeads('', false)
})

</script>
<template>
  <!-- Basic information -->
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
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
              <input type="hidden" v-model="form.ops_vessel_id" />
            </label> 
  </div>
  <!-- South Sectors -->
  <div class="relative">
    <!-- <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400"> -->
      <!-- <legend class="px-2 text-gray-700 dark:text-gray-300">Groups <span class="text-red-500">*</span></legend> -->
      <div class="mt-2 flex justify-between">

        <fieldset class="w-1/2 mx-2 px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
          <legend class="px-2 text-gray-700 dark:text-gray-300">Cost Groups <span class="text-red-500">*</span></legend>

          <div v-for="(singleHead,index) in heads" :key="index" class="mb-2">
            <label class="mb-2 text-gray-600 dark:text-gray-400">
              <input type="checkbox" @change="checkSubHead(singleHead.id, index)" v-model="form.heads" :id="'heads' + index" class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" name="accountType" :value="singleHead?.id">
            </label>
            <span class="ml-2 top-1 relative font-medium hover:text-purple-00 hover:cursor-pointer" @click="getSubHead(singleHead.id,index)">{{ singleHead?.name }}</span>
          </div>
        </fieldset>

        <fieldset class="w-1/2 mx-2 px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
          <legend class="px-2 text-gray-700 dark:text-gray-300">Group Heads <span class="text-red-500">*</span></legend>

          <div v-for="(singleHead,index) in subheads" :key="index" class="">
            <label class="mb-2 text-gray-600 dark:text-gray-400">
              <input type="checkbox" v-model="form.heads" :id="'heads' + index" class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" name="accountType" :value="singleHead?.id">
              <span class="ml-2 font-medium hover:text-purple-00 hover:cursor-pointer">{{ singleHead?.name }}</span>
            </label>
          </div>
        </fieldset>

      </div>
    <!-- </fieldset> -->

    <!--  -->
  </div>


  <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}

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
>>> {
  --vs-controls-color: #374151;
  --vs-border-color: #4b5563;

  --vs-dropdown-bg: #282c34;
  --vs-dropdown-color: #eeeeee;
  --vs-dropdown-option-color: #eeeeee;

  --vs-selected-bg: #664cc3;
  --vs-selected-color: #374151;

  --vs-search-input-color: #4b5563;

  --vs-dropdown-option--active-bg: #664cc3;
  --vs-dropdown-option--active-color: #eeeeee;
}
</style>