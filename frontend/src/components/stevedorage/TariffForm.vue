<script setup>
import {onMounted} from "@vue/runtime-core";
import {computed, ref, watch} from "vue";
import NProgress from "nprogress";
import Error from "../Error.vue";
import usePort from "../../composables/usePort";
const { ports, portName, getPortsByNameOrCode } = usePort();

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
  page: {
    required: false,
    default: {},
  }
});

//watch props.form.port_code
watch(() => props.form.port_code, (newValue, oldValue) => {
  props.form.name = props.form.name ?? props.form.port_code;
});

function addTariffHead() {
  let obj = {
    serial_no: props.form.tariff_heads.length + 1,
    name: '',
    code: '',
    vendor: '',
  };

  let chargeObjLoadingTs = {operation_type: 'Loading', load_status: 'TS', charge_code: '', currency: '', fcl_20: '', fcl_40: '', fcl_45: '', mty_20: '', mty_40: '',
    mty_45: '', rf_20: '', rf_40: '', rf_45: '', fr_20: '', fr_40: '', fr_45: '', tk_20: '', tk_40: '', tk_45: '', dg_20: '', dg_40: '', dg_45: '', serial_no: props.form.tariff_heads.length + 1
  };

  let chargeObjLoadingLc = {operation_type: 'Loading', load_status: 'LC', charge_code: '', currency: '', fcl_20: '', fcl_40: '', fcl_45: '', mty_20: '', mty_40: '',
    mty_45: '', rf_20: '', rf_40: '', rf_45: '', fr_20: '', fr_40: '', fr_45: '', tk_20: '', tk_40: '', tk_45: '', dg_20: '', dg_40: '', dg_45: '', serial_no: props.form.tariff_heads.length + 1
  };

  let chargeObjDischargeTs = {operation_type: 'Discharge', load_status: 'TS', charge_code: '', currency: '', fcl_20: '', fcl_40: '', fcl_45: '', mty_20: '', mty_40: '',
    mty_45: '', rf_20: '', rf_40: '', rf_45: '', fr_20: '', fr_40: '', fr_45: '', tk_20: '', tk_40: '', tk_45: '', dg_20: '', dg_40: '', dg_45: '', serial_no: props.form.tariff_heads.length + 1
  };

  let chargeObjDischargeLc = {operation_type: 'Discharge', load_status: 'LC', charge_code: '', currency: '', fcl_20: '', fcl_40: '', fcl_45: '', mty_20: '', mty_40: '', mty_45: '', rf_20: '', rf_40: '', rf_45: '', fr_20: '',
    fr_40: '', fr_45: '', tk_20: '', tk_40: '', tk_45: '', dg_20: '', dg_40: '', dg_45: '', serial_no: props.form.tariff_heads.length + 1
  };

  props.form.tariff_heads.push(obj);
  props.form.tariff_charges.push(chargeObjLoadingTs);
  props.form.tariff_charges.push(chargeObjLoadingLc);
  props.form.tariff_charges.push(chargeObjDischargeTs);
  props.form.tariff_charges.push(chargeObjDischargeLc);
}

function removeTariffHead(index){
  // let initialSerial = 1;
  // if(props.page === 'update'){
  //   props.form.tariff_heads.forEach((head, headIndex) => {
  //     // set serial no for each tariff head
  //     props.form.tariff_heads[headIndex].serial_no = initialSerial;
  //     initialSerial++;
  //   });
  //   // get tariff charges only loading ts
  //   let loadingTs = props.form.tariff_charges.filter(function (el) {
  //     return el.operation_type === 'Loading' && el.load_status === 'TS';
  //   });
  //
  //
  // }

  //get index value
  let serial_no = props.form.tariff_heads[index].serial_no;

  //remove items from tariff_charges where serial_no = serial_no
  props.form.tariff_charges = props.form.tariff_charges.filter(function (el) {
    return el.serial_no !== serial_no;
  });

  props.form.tariff_heads.splice(index, 1);
}

function fetchOptions(search, loading) {
  getPortsByNameOrCode(search);
}

function placeCode(e,serial_no) {
  let code = e.target.value;

  props.form.tariff_charges.forEach((charge, index) => {
    if(charge.serial_no === serial_no) {
      props.form.tariff_charges[index].charge_code = code;
    }
  });
}

function placeCurrency(e,serial_no) {
  let currency = e.target.value;

  props.form.tariff_charges.forEach((charge, index) => {
    if(charge.serial_no === serial_no) {
      props.form.tariff_charges[index].currency = currency;
    }
  });
}

function loadingSameAsTs(e){
  if(e.target.checked){
    //copy value from previous table row in next table row and disable the input field
    props.form.tariff_charges.forEach((charge, index) => {
      if(charge.operation_type === 'Loading' && charge.load_status === 'LC'){
        props.form.tariff_charges[index].fcl_20 = props.form.tariff_charges[index-1].fcl_20;
        props.form.tariff_charges[index].fcl_40 = props.form.tariff_charges[index-1].fcl_40;
        props.form.tariff_charges[index].fcl_45 = props.form.tariff_charges[index-1].fcl_45;
        props.form.tariff_charges[index].mty_20 = props.form.tariff_charges[index-1].mty_20;
        props.form.tariff_charges[index].mty_40 = props.form.tariff_charges[index-1].mty_40;
        props.form.tariff_charges[index].mty_45 = props.form.tariff_charges[index-1].mty_45;
        props.form.tariff_charges[index].rf_20 = props.form.tariff_charges[index-1].rf_20;
        props.form.tariff_charges[index].rf_40 = props.form.tariff_charges[index-1].rf_40;
        props.form.tariff_charges[index].rf_45 = props.form.tariff_charges[index-1].rf_45;
        props.form.tariff_charges[index].fr_20 = props.form.tariff_charges[index-1].fr_20;
        props.form.tariff_charges[index].fr_40 = props.form.tariff_charges[index-1].fr_40;
        props.form.tariff_charges[index].fr_45 = props.form.tariff_charges[index-1].fr_45;
        props.form.tariff_charges[index].tk_20 = props.form.tariff_charges[index-1].tk_20;
        props.form.tariff_charges[index].tk_40 = props.form.tariff_charges[index-1].tk_40;
        props.form.tariff_charges[index].tk_45 = props.form.tariff_charges[index-1].tk_45;
        props.form.tariff_charges[index].dg_20 = props.form.tariff_charges[index-1].dg_20;
        props.form.tariff_charges[index].dg_40 = props.form.tariff_charges[index-1].dg_40;
        props.form.tariff_charges[index].dg_45 = props.form.tariff_charges[index-1].dg_45;
      }
  });

  //table class name loading_lc_container_charges to disable input field
  // document.getElementsByClassName('loading_lc_container_charges')[0].style.pointerEvents = 'none';
  //
  // document.getElementsByClassName('loading_lc_container_charges')[0].style.opacity = '0.5';

} else {
  //clear value from next table row and enable the input field
  props.form.tariff_charges.forEach((charge, index) => {
    if(charge.operation_type === 'Loading' && charge.load_status === 'LC'){
      props.form.tariff_charges[index].fcl_20 = '';
      props.form.tariff_charges[index].fcl_40 = '';
      props.form.tariff_charges[index].fcl_45 = '';
      props.form.tariff_charges[index].mty_20 = '';
      props.form.tariff_charges[index].mty_40 = '';
      props.form.tariff_charges[index].mty_45 = '';
      props.form.tariff_charges[index].rf_20 = '';
      props.form.tariff_charges[index].rf_40 = '';
      props.form.tariff_charges[index].rf_45 = '';
      props.form.tariff_charges[index].fr_20 = '';
      props.form.tariff_charges[index].fr_40 = '';
      props.form.tariff_charges[index].fr_45 = '';
      props.form.tariff_charges[index].tk_20 = '';
      props.form.tariff_charges[index].tk_40 = '';
      props.form.tariff_charges[index].tk_45 = '';
      props.form.tariff_charges[index].dg_20 = '';
      props.form.tariff_charges[index].dg_40 = '';
      props.form.tariff_charges[index].dg_45 = '';
    }
  });

 }
}


function dischargeSameAsTs(e){
if(e.target.checked){
    //copy value from previous table row in next table row and disable the input field
    props.form.tariff_charges.forEach((charge, index) => {
      if(charge.operation_type === 'Discharge' && charge.load_status === 'LC') {
        props.form.tariff_charges[index].fcl_20 = props.form.tariff_charges[index - 1].fcl_20;
        props.form.tariff_charges[index].fcl_40 = props.form.tariff_charges[index - 1].fcl_40;
        props.form.tariff_charges[index].fcl_45 = props.form.tariff_charges[index - 1].fcl_45;
        props.form.tariff_charges[index].mty_20 = props.form.tariff_charges[index - 1].mty_20;
        props.form.tariff_charges[index].mty_40 = props.form.tariff_charges[index - 1].mty_40;
        props.form.tariff_charges[index].mty_45 = props.form.tariff_charges[index - 1].mty_45;
        props.form.tariff_charges[index].rf_20  = props.form.tariff_charges[index - 1].rf_20;
        props.form.tariff_charges[index].rf_40  = props.form.tariff_charges[index - 1].rf_40;
        props.form.tariff_charges[index].rf_45  = props.form.tariff_charges[index - 1].rf_45;
        props.form.tariff_charges[index].fr_20  = props.form.tariff_charges[index - 1].fr_20;
        props.form.tariff_charges[index].fr_40  = props.form.tariff_charges[index - 1].fr_40;
        props.form.tariff_charges[index].fr_45  = props.form.tariff_charges[index - 1].fr_45;
        props.form.tariff_charges[index].tk_20  = props.form.tariff_charges[index - 1].tk_20;
        props.form.tariff_charges[index].tk_40  = props.form.tariff_charges[index - 1].tk_40;
        props.form.tariff_charges[index].tk_45  = props.form.tariff_charges[index - 1].tk_45;
        props.form.tariff_charges[index].dg_20  = props.form.tariff_charges[index - 1].dg_20;
        props.form.tariff_charges[index].dg_40  = props.form.tariff_charges[index - 1].dg_40;
        props.form.tariff_charges[index].dg_45  = props.form.tariff_charges[index - 1].dg_45;
      }
    });

  // document.getElementsByClassName('discharge_lc_container_charges')[0].style.pointerEvents = 'none';
  //
  // document.getElementsByClassName('discharge_lc_container_charges')[0].style.opacity = '0.5';

  } else {
    //clear value from next table row and enable the input field
    props.form.tariff_charges.forEach((charge, index) => {
      if(charge.operation_type === 'Discharge' && charge.load_status === 'LC'){
        props.form.tariff_charges[index].fcl_20 = '';
        props.form.tariff_charges[index].fcl_40 = '';
        props.form.tariff_charges[index].fcl_45 = '';
        props.form.tariff_charges[index].mty_20 = '';
        props.form.tariff_charges[index].mty_40 = '';
        props.form.tariff_charges[index].mty_45 = '';
        props.form.tariff_charges[index].rf_20  = '';
        props.form.tariff_charges[index].rf_40  = '';
        props.form.tariff_charges[index].rf_45  = '';
        props.form.tariff_charges[index].fr_20  = '';
        props.form.tariff_charges[index].fr_40  = '';
        props.form.tariff_charges[index].fr_45  = '';
        props.form.tariff_charges[index].tk_20  = '';
        props.form.tariff_charges[index].tk_40  = '';
        props.form.tariff_charges[index].tk_45  = '';
        props.form.tariff_charges[index].dg_20  = '';
        props.form.tariff_charges[index].dg_40  = '';
        props.form.tariff_charges[index].dg_45  = '';
      }
    });
   }
}

// watch(props.form, (value) => {
//   props.form.name = props.form.port_code;
// }, { deep: true });

</script>


<template>
    <!-- Basic information -->
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-3 text-sm md:w-2/6">
            <span class="text-gray-700 dark:text-gray-300">Port Code <span class="text-red-500">*</span></span>
<!--            <input type="text" v-model="form.port_code" required placeholder="Ex: BDCGP" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />-->
          <v-select v-model="form.port_code" @search="fetchOptions" value="id" :options="portName" :reduce="portName => portName.code" label="code_name" placeholder="Port code" class="w-full mt-1 placeholder-gray-600"></v-select>

          <Error v-if="errors?.port_code" :errors="errors.port_code" />
        </label>
        <label class="block w-full mt-3 text-sm md:w-2/6">
            <span class="text-gray-700 dark:text-gray-300">Tariff Name <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.name" required placeholder="BDCGP-July 2023" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          <Error v-if="errors?.name" :errors="errors.name" />
        </label>
      <label class="block w-full mt-3 text-sm md:w-2/6">
        <span class="text-gray-700 dark:text-gray-300">Cost Type <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.cost_type" required placeholder="Ex: Stevedorage" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        <Error v-if="errors?.cost_type" :errors="errors.cost_type" />
      </label>
      <label class="block w-full mt-3 text-sm md:w-2/6">
        <span class="text-gray-700 dark:text-gray-300">Expire Date <span class="text-red-500">*</span></span>
        <input type="date" v-model="form.expire_date" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        <Error v-if="errors?.expire_date" :errors="errors.expire_date" />
      </label>
      <label class="block w-full mt-3 text-sm md:w-2/6">
        <span class="text-gray-700 dark:text-gray-300">Currency <span class="text-red-500">*</span></span>
        <select v-model="form.currency" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          <option value="" disabled selected>--Choose Option--</option>
          <option value="USD">USD</option>
          <option value="BDT">BDT</option>
          <option value="SGD">SGD</option>
          <option value="MYR">MYR</option>
        </select>
        <Error v-if="errors?.currency" :errors="errors.currency" />
      </label>
<!--      <label class="block w-full mt-3 text-sm md:w-2/6">-->
<!--        <span class="text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></span>-->
<!--        <select v-model="form.status" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--          <option value="" disabled selected>&#45;&#45;Choose Option&#45;&#45;</option>-->
<!--          <option value="0">Save</option>-->
<!--          <option value="1">Save & Active</option>-->
<!--        </select>-->
<!--        <Error v-if="errors?.status" :errors="errors.status" />-->
<!--      </label>-->
    </div>
  <div class="flex flex-col justify-center w-full">
    <label class="block w-full mt-1 text-sm md:w-2/6">
      <span class="text-gray-700 dark:text-gray-300">Remarks</span>
      <textarea type="text" v-model="form.short_note" placeholder="Remarks" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      <Error v-if="errors?.short_note" :errors="errors.short_note" />
    </label>
  </div>
    <!-- tariff_heads -->
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 dark:text-gray-300">Cost Heads <span class="text-red-500">*</span></legend>
        <table class="w-full whitespace-no-wrap" id="table">
            <thead>
                <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                    <th class="px-4 py-3 align-bottom">Charge Name</th>
                    <th class="px-4 py-3 align-bottom">Code</th>
<!--                    <th class="px-4 py-3 align-bottom">Currency</th>-->
                    <th class="px-4 py-3 align-bottom">Vendor</th>
                    <th class="px-4 py-3 text-center align-bottom">Action</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                <tr class="text-gray-700 dark:text-gray-400" v-for="(tariffHead, index) in form.tariff_heads" :key="tariffHead.id">
                    <td class="px-1 py-1" style="width: 25%">
                      <input type="text" required v-model="form.tariff_heads[index].name" placeholder="Charge name" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    </td>
                  <td class="px-1 py-1" style="width: 25%">
                    <input type="text" required v-model="form.tariff_heads[index].code" @input="placeCode($event,form.tariff_heads[index].serial_no)" placeholder="Code" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                  </td>
<!--                  <td class="px-1 py-1" style="width: 25%">-->
<!--                    <input type="text" required v-model="form.tariff_heads[index].currency" placeholder="Currency" @input="placeCurrency($event,form.tariff_heads[index].serial_no)" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--                  </td>-->
                  <td class="px-1 py-1">
                    <input type="text" required v-model="form.tariff_heads[index].vendor" placeholder="Vendor" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                  </td>
                    <td class="px-1 py-1 text-center">
                        <button v-if="index!=0" type="button" @click="removeTariffHead(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                        </button>
                      <button v-else type="button" @click="addTariffHead()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                      </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </fieldset>

  <!-- tariff_charges -->
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Loading <span class="text-red-500">*</span></legend>
    <table class="w-full whitespace-no-wrap loading_ts_container_charges" id="table">
      <thead>
      <tr style="background-color: #2c8038" class="text-xs font-semibold tracking-wide text-center uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th colspan="20" style="color: white" class="px-4 py-3 align-bottom">TS CONTAINER CHARGES</th>
      </tr>
      <tr style="background-color: #d0cfcf" class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th rowspan="2">CHARGE CODE</th>
<!--        <th rowspan="2">CURR.</th>-->
        <th colspan="3">FULL</th>
        <th colspan="3">EMPTY</th>
        <th colspan="3">REFFER</th>
        <th colspan="3">TANK</th>
        <th colspan="3">AKWARD</th>
<!--        <th colspan="3">DG</th>-->
      </tr>
      <tr style="background-color: #d0cfcf" class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th>20"</th>
        <th>40"</th>
        <th>45"</th>
        <th>20"</th>
        <th>40"</th>
        <th>45"</th>
        <th>20"</th>
        <th>40"</th>
        <th>45"</th>
        <th>20"</th>
        <th>40"</th>
        <th>45"</th>
        <th>20"</th>
        <th>40"</th>
        <th>45"</th>
<!--        <th>20"</th>-->
<!--        <th>40"</th>-->
<!--        <th>45"</th>-->
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(tariffCharge, index) in form.tariff_charges" :key="tariffCharge.id">
        <template v-if="form.tariff_charges[index].operation_type == 'Loading' && form.tariff_charges[index].load_status == 'TS'">
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].charge_code" placeholder="code" readonly class="block vms-readonly-input w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
<!--          <td class="px-1 py-1">-->
<!--            <input type="text" v-model="form.tariff_charges[index].currency" placeholder="Curr." readonly class="block vms-readonly-input w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--          </td>-->
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fcl_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fcl_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fcl_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].mty_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].mty_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].mty_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].rf_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].rf_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].rf_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fr_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fr_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fr_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].tk_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].tk_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].tk_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
<!--          <td class="px-1 py-1">-->
<!--            <input type="text" v-model="form.tariff_charges[index].dg_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--          </td>-->
<!--          <td class="px-1 py-1">-->
<!--            <input type="text" v-model="form.tariff_charges[index].dg_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--          </td>-->
<!--          <td class="px-1 py-1">-->
<!--            <input type="text" v-model="form.tariff_charges[index].dg_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--          </td>-->
        </template>
      </tr>
      </tbody>
    </table>
    <div class="flex items-center mt-2">
      <input type="checkbox" @input="loadingSameAsTs($event)"><span class="ml-2 font-bold text-xs">Same as TS</span>
    </div>
    <table class="w-full whitespace-no-wrap mt-2 loading_lc_container_charges" id="table">
      <thead>
      <tr style="background-color: #2c8038" class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th colspan="22" style="color: white" class="px-4 py-3 align-bottom">LC CONTAINER CHARGES</th>
      </tr>
      <tr style="background-color: #d0cfcf;" class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th rowspan="2">CHARGE CODE</th>
<!--        <th rowspan="2">CURR.</th>-->
        <th colspan="3">FULL</th>
        <th colspan="3">EMPTY</th>
        <th colspan="3">REFFER</th>
        <th colspan="3">TANK</th>
        <th colspan="3">AKWARD</th>
<!--        <th colspan="3">DG</th>-->
      </tr>
      <tr style="background-color: #d0cfcf" class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th>20"</th>
        <th>40"</th>
        <th>45"</th>
        <th>20"</th>
        <th>40"</th>
        <th>45"</th>
        <th>20"</th>
        <th>40"</th>
        <th>45"</th>
        <th>20"</th>
        <th>40"</th>
        <th>45"</th>
        <th>20"</th>
        <th>40"</th>
        <th>45"</th>
<!--        <th>20"</th>-->
<!--        <th>40"</th>-->
<!--        <th>45"</th>-->
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(tariffCharge, index) in form.tariff_charges" :key="tariffCharge.id">
        <template v-if="form.tariff_charges[index].operation_type == 'Loading' && form.tariff_charges[index].load_status == 'LC'">
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].charge_code" placeholder="code" readonly class="block vms-readonly-input w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
<!--          <td class="px-1 py-1">-->
<!--            <input type="text" v-model="form.tariff_charges[index].currency" placeholder="Curr." readonly class="block vms-readonly-input w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--          </td>-->
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fcl_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fcl_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fcl_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].mty_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].mty_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].mty_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].rf_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].rf_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].rf_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fr_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fr_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fr_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].tk_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].tk_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].tk_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
<!--          <td class="px-1 py-1">-->
<!--            <input type="text" v-model="form.tariff_charges[index].dg_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--          </td>-->
<!--          <td class="px-1 py-1">-->
<!--            <input type="text" v-model="form.tariff_charges[index].dg_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--          </td>-->
<!--          <td class="px-1 py-1">-->
<!--            <input type="text" v-model="form.tariff_charges[index].dg_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--          </td>-->
        </template>
      </tr>
      </tbody>
    </table>
  </fieldset>

  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Discharge <span class="text-red-500">*</span></legend>
    <table class="w-full whitespace-no-wrap discharge_ts_container_charges" id="table">
      <thead>
      <tr style="background-color: #b74646" class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th colspan="20" class="px-4 py-3 align-bottom" style="color: white">TS CONTAINER CHARGES</th>
      </tr>
      <tr style="background-color: #d0cfcf" class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th rowspan="2">CHARGE CODE</th>
        <!--        <th rowspan="2">CURR.</th>-->
        <th colspan="3">FULL</th>
        <th colspan="3">EMPTY</th>
        <th colspan="3">REFFER</th>
        <th colspan="3">TANK</th>
        <th colspan="3">AKWARD</th>
<!--        <th colspan="3">DG</th>-->
      </tr>
      <tr style="background-color: #d0cfcf" class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th>20"</th>
        <th>40"</th>
        <th>45"</th>
        <th>20"</th>
        <th>40"</th>
        <th>45"</th>
        <th>20"</th>
        <th>40"</th>
        <th>45"</th>
        <th>20"</th>
        <th>40"</th>
        <th>45"</th>
        <th>20"</th>
        <th>40"</th>
        <th>45"</th>
<!--        <th>20"</th>-->
<!--        <th>40"</th>-->
<!--        <th>45"</th>-->
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(tariffCharge, index) in form.tariff_charges" :key="tariffCharge.id">
        <template v-if="form.tariff_charges[index].operation_type == 'Discharge' && form.tariff_charges[index].load_status == 'TS'">
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].charge_code" placeholder="code" readonly class="block vms-readonly-input w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
<!--          <td class="px-1 py-1">-->
<!--            <input type="text" v-model="form.tariff_charges[index].currency" placeholder="Curr." readonly class="block vms-readonly-input w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--          </td>-->
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fcl_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fcl_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fcl_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].mty_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].mty_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].mty_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].rf_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].rf_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].rf_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fr_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fr_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fr_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].tk_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].tk_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].tk_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
<!--          <td class="px-1 py-1">-->
<!--            <input type="text" v-model="form.tariff_charges[index].dg_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--          </td>-->
<!--          <td class="px-1 py-1">-->
<!--            <input type="text" v-model="form.tariff_charges[index].dg_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--          </td>-->
<!--          <td class="px-1 py-1">-->
<!--            <input type="text" v-model="form.tariff_charges[index].dg_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--          </td>-->
        </template>
      </tr>
      </tbody>
    </table>
    <div class="flex items-center mt-2">
      <input type="checkbox" @input="dischargeSameAsTs($event)"><span class="ml-2 font-bold text-xs">Same as TS</span>
    </div>
    <table class="w-full whitespace-no-wrap mt-2 discharge_lc_container_charges" id="table">
      <thead>
      <tr style="background-color: #b74646" class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th colspan="20" class="px-4 py-3 align-bottom" style="color: white">LC CONTAINER CHARGES</th>
      </tr>
      <tr style="background-color: #d0cfcf" class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th rowspan="2">CHARGE CODE</th>
        <!--        <th rowspan="2">CURR.</th>-->
        <th colspan="3">FULL</th>
        <th colspan="3">EMPTY</th>
        <th colspan="3">REFFER</th>
        <th colspan="3">TANK</th>
        <th colspan="3">AKWARD</th>
<!--        <th colspan="3">DG</th>-->
      </tr>
      <tr style="background-color: #d0cfcf" class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th>20"</th>
        <th>40"</th>
        <th>45"</th>
        <th>20"</th>
        <th>40"</th>
        <th>45"</th>
        <th>20"</th>
        <th>40"</th>
        <th>45"</th>
        <th>20"</th>
        <th>40"</th>
        <th>45"</th>
        <th>20"</th>
        <th>40"</th>
        <th>45"</th>
<!--        <th>20"</th>-->
<!--        <th>40"</th>-->
<!--        <th>45"</th>-->
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(tariffCharge, index) in form.tariff_charges" :key="tariffCharge.id">
        <template v-if="form.tariff_charges[index].operation_type == 'Discharge' && form.tariff_charges[index].load_status == 'LC'">
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].charge_code" placeholder="code" readonly class="block vms-readonly-input w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
<!--          <td class="px-1 py-1">-->
<!--            <input type="text" v-model="form.tariff_charges[index].currency" placeholder="Curr." readonly class="block vms-readonly-input w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--          </td>-->
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fcl_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fcl_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fcl_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].mty_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].mty_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].mty_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].rf_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].rf_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].rf_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fr_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fr_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].fr_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].tk_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].tk_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
          <td class="px-1 py-1">
            <input type="text" v-model="form.tariff_charges[index].tk_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
<!--          <td class="px-1 py-1">-->
<!--            <input type="text" v-model="form.tariff_charges[index].dg_20" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--          </td>-->
<!--          <td class="px-1 py-1">-->
<!--            <input type="text" v-model="form.tariff_charges[index].dg_40" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--          </td>-->
<!--          <td class="px-1 py-1">-->
<!--            <input type="text" v-model="form.tariff_charges[index].dg_45" placeholder="" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--          </td>-->
        </template>
      </tr>
      </tbody>
    </table>
  </fieldset>
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
  @apply block text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
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