<script setup>
import Error from "../Error.vue";
import usePort from "../../composables/usePort";
import { watch, ref } from 'vue';
import useVoyageExpenditureHead from "../../composables/finance/useVoyageExpenditureHead";

const { portName, getPortsByNameOrCode } = usePort();
const { expenditureHead, showHead, isLoading, errors } = useVoyageExpenditureHead();
const subheads = ref([]);

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  page: {
    required: false,
    default: {}
  },
  heads: { type: [Object, Array], required: true },
  errors: { type: [Object, Array], required: false },
});

function fetchOptions(search, loading) {
  getPortsByNameOrCode(search);
}

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

function checkSubHead(headId, headIndex) {
  console.log()
  if(props.form.heads.includes(headId)) {
    props.heads[headIndex].subheads.map(({id})=>{ 
      props.form.heads.push(id)
    });
  }
}

watch(() => props.form.port_name, (value) => {
  if (value) {
    props.form.port = value.code;
  }
}, {deep: true});

</script>
<template>
  <!-- Basic information -->
  <div class="flex flex-col justify-center w-full">
    <label class="block w-full mt-3 text-sm md:w-3/6">
      <span class="text-gray-700 dark:text-gray-300">Port <span class="text-red-500">*</span></span>
      <v-select v-model="form.port_name" :id="'port_name' + index" @search="fetchOptions" value="id" :options="portName" label="code_name" placeholder="Enter Port Code or Name" class="mt-1 placeholder-gray-600 w-full"></v-select>
      <input type="hidden" v-model="form.port" name="port_code" :id="'port_code' + index" />
      <Error v-if="errors?.port" :errors="errors.port" />
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