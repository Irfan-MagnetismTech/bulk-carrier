<script setup>
import { ref, watch, onMounted, onBeforeUnmount, computed } from 'vue';
import Error from "../../Error.vue";
import useCreditSale from "../../../composables/revenue/useCreditSale.js";
import useSaleRate from "../../../composables/revenue/useSaleRate.js";
import moment from "moment";
import useAuth from '../../../services/auth';
import useMaterial from '../../../composables/scm/useMaterial';
import useCustomer from '../../../composables/configuration/useCustomer.js';
import useHelper from '../../../composables/useHelper.js';
const { creditSales, searchCreditSale, storeCreditSale, saleStatusBySlip, checkSlipValidity, updateCreditSale } = useCreditSale();
const { material, materials, searchMobilWithRate } = useMaterial();
const { saleRate, showSaleRate } = useSaleRate();
const { customer, creditInfo, customers, searchCustomerByCode, showCustomer, customerVehicles, getCustomerVehicles } = useCustomer();
const { username, shift } = useAuth();
const { numberFormat } = useHelper();

const props = defineProps({
  creditSale: { type: Object, required: true },
  mobilModel: { type: Object, required: true },
  vehicles: { type: Object, required: true },
  units: { type: Object, required: true },
  paymentMethods: { type: Object, required: true },
  formType: { type: String, required: true },
  creditSaleId: { type: String, required: true },

});

const now = moment();
const hasUpdated = ref(0);

props.creditSale.entry_date = now.format("YYYY-MM-DD");
props.creditSale.entry_time = now.format("hh:mm A");

function fetchMobil(query, loading) {
  searchMobilWithRate(query, loading);
  loading(true)
}

function fetchCustomer(query, loading) {
  searchCustomerByCode(query, loading);
  loading(true)
}

function fetchVehicle() {
  // getCustomerVehicles(props.creditSale.customer_id);
}

watch(() => props.creditSale.fuel, (fuels) => {
  for (let index in fuels) {

    props.creditSale.fuel[index].customer_vehicle_id = props.creditSale.fuel[index].vehicle?.id

    if(index > 2 ) {
      let item = fuels[index];

      props.creditSale.fuel[index].unit_price = item?.material?.sale_rate?.rate ?? 0
      props.creditSale.fuel[index].sale_rate_id = item?.material?.sale_rate?.id ?? 0
      props.creditSale.fuel[index].material_category_id = item?.material?.category_id ?? 0
      props.creditSale.fuel[index].material_id = item?.material?.id ?? 0
      props.creditSale.fuel[index].unit = item?.material?.unit
      props.creditSale.fuel[index].in_stock = item?.material?.stock_sum_quantity


      if(props.creditSale.fuel[index].in_stock < props.creditSale.fuel[index].quantity) {
        alert("You can't add more than in stock")
        props.creditSale.fuel[index].quantity = props.creditSale.fuel[index].in_stock
      }
    }

    if(props.creditSale.fuel[index].quantity > props.creditSale.fuel[index].in_stock ) {
      alert("You can't add more than in stock")
      props.creditSale.fuel[index].quantity = props.creditSale.fuel[index].in_stock
    }
  }
  calculatedAmount();

}, {deep : true});



watch(() => props.creditSale.customer, (value) => {
  props.creditSale.customer_id = value?.id;
  props.creditSale.customer_name = value?.name;

  props.creditSale.billing_address = value?.billing_address
  props.creditSale.billing_email = value?.billing_email

  // fetchVehicle();
  getCustomerVehicles(props.creditSale.customer_id);
  showCustomer(props.creditSale.customer_id)

}, { deep: true });

watch(() => props.creditSale.slip_no, (value) => {
  if(!props.creditSale.customer_id) {
    alert('Please choose customer first.')
  } else {
    checkSlipValidity(props.creditSale.customer_id, value)
  }
}, { deep: true })

watch(() => saleStatusBySlip, (value) => {
  console.log('saleStatusBySlip', value?.value)
  if(value?.value?.id == props.creditSale?.sale?.id) {

  } else if(value?.value != null){
    alert('This slip is already sold.')
    props.creditSale.slip_no = ''
  }
}, { deep: true })

const grandTotal = computed(() => {
  let total = 0;
  previewPanel.value.materials.forEach((fuel) => {
    let amount = (fuel.amount != '' || fuel.amount != undefined) ? fuel.amount : 0;
    total += parseFloat(amount);
  });

  if(isNaN(total)) {
    total = 0
  }

  props.creditSale.total_amount = total

  if(props.creditSale.paid_amount < 0) {
    props.creditSale.paid_amount = Math.abs(props.creditSale.paid_amount)
  }

  let paidAmount = props.creditSale.paid_amount

  if(paidAmount == '' || paidAmount == undefined) {
    paidAmount = 0
  }
  let dueAmount = total - parseFloat(paidAmount)
  props.creditSale.due_amount = dueAmount

  return parseFloat(total).toFixed(2);

});

const calculatedAmount = () => {
  for(let index in props.creditSale.fuel) {
    let qty = (props.creditSale.fuel[index].quantity != '') ? props.creditSale.fuel[index].quantity : 0;
    let unit_price = (props.creditSale.fuel[index].unit_price != '') ? props.creditSale.fuel[index].unit_price : 0;
    let amount = parseFloat(qty) * parseFloat(unit_price);

    if(isNaN(amount)) {
      amount = 0
    }
    props.creditSale.fuel[index].amount = parseFloat(amount).toFixed(2);
  }


  for(let index in props.creditSale.mobil) {
    let qty = (props.creditSale.mobil[index].quantity != '' || props.creditSale.mobil[index].quantity != undefined) ? props.creditSale.mobil[index].quantity : 0;
    let unit_price = (props.creditSale.mobil[index].unit_price != '' || props.creditSale.mobil[index].unit_price != undefined) ? props.creditSale.mobil[index].unit_price : 0;
    let amount = parseFloat(qty) * parseFloat(unit_price);
    if(isNaN(amount)) {
      amount = 0
    }
    props.creditSale.mobil[index].amount = parseFloat(amount).toFixed(2);
  }
}

onMounted(() => {
  if(props.formType=='create') {
    props.creditSale.date = moment(props.creditSale?.created_at).format("YYYY-MM-DD")
    props.creditSale.shift = shift
  }
});

onBeforeUnmount(() => {
      document.removeEventListener('keydown', handleEvent);
    });

/**
 * Code for shortcut key for sales form
 */

const openTab = ref(3);
let keyPressed = "";
let lastKeyPressTime = 0;
const timeThreshold = 300;
document.addEventListener("keydown", handleEvent);

function handleEvent(event) {
  const key = event.key.toLowerCase();
  const currentTime = new Date().getTime();
  if (key === "d") {
    if (keyPressed === "d" && currentTime - lastKeyPressTime < timeThreshold) {
      openTab.value = 0;
      setTimeout(() => {
        document.getElementById("0").focus();
      }, 0);
      keyPressed = "";
    } else {
      keyPressed = "d";
      lastKeyPressTime = currentTime;
    }
  } else if(key === "o"){
    if (keyPressed === "o" && currentTime - lastKeyPressTime < timeThreshold) {
      openTab.value = 1;
      setTimeout(() => {
        document.getElementById("1").focus();
      }, 0);
      keyPressed = "";
    } else {
      keyPressed = "o";
      lastKeyPressTime = currentTime;
    }
  } else if(key === "p"){
    if (keyPressed === "p" && currentTime - lastKeyPressTime < timeThreshold) {
      openTab.value = 2;
      setTimeout(() => {
        document.getElementById("2").focus();
      }, 0);
      keyPressed = "";
    } else {
      keyPressed = "p";
      lastKeyPressTime = currentTime;
    }
  } else if(key === "l"){
    if (keyPressed === "l" && currentTime - lastKeyPressTime < timeThreshold) {
      openTab.value = 3;
      setTimeout(() => {
        document.getElementById("3").focus();
      }, 0);
      keyPressed = "";
    } else {
      keyPressed = "l";
      lastKeyPressTime = currentTime;
    }
  } else if(event.key === "Enter" || event.code === "Enter"){
    storeToPreview();
  }  else if(key === "x"){
    saveCreditSale();
  } else {
    keyPressed = "";
  }
}

function saveCreditSale() {

  let saleInfo = {
    ... props.creditSale,
    ...previewPanel.value
  }

  console.log("saleInfo:", saleInfo)
  // let saleInfo = {
  //     ... props.creditSale,
  //     ... materials
  // }
  if(props.formType == 'edit' && saleInfo.customer_id != '' && saleInfo.slip_no != '') {
    updateCreditSale(saleInfo, props.creditSaleId)
  } else if(saleInfo.customer_id != '' && saleInfo.slip_no != '') {
    storeCreditSale(saleInfo)
  } else {
    alert('Customer Info and Slip No is Mandatory')
  }
}

const previewPanel = ref({
  materials: []
});

function storeToPreview() {

  let activeIndex = openTab.value;
  let item = props.creditSale.fuel[activeIndex];

  if(item.customer_vehicle_id == '' || item.customer_vehicle_id == undefined) {
    alert('You must choose a vehicle.')
    return false;
  }

  if(item.quantity > 0 || item.quantity < 0 && item.unit_price > 0) {
    if(previewPanel.value.materials.length > 0) {

      const materialIndex = previewPanel.value.materials.findIndex(obj => obj.material_id === item.material_id);

      if(materialIndex !== -1) {
        previewPanel.value.materials[materialIndex] = { ...item }
      } else {
        previewPanel.value.materials.push({ ...item })
      }
    } else {
      if(item.quantity > 0 || item.quantity < 0 && item.unit_price > 0) {
        previewPanel.value.materials.push({ ...item })
        item.quantity = null;
      }
    }
    item.quantity = null;
    item.material = null;
    openTab.value = 3
  }
}

function removeFuelPreviewPanel(index){
  previewPanel.value.materials.splice(index, 1);
}

watch(() => props.creditSale, (value) => {

  if(hasUpdated.value == 0) {

    for(let index in props.creditSale.materials) {
      let item = props.creditSale.materials[index];
      item.customer_vehicle_id = item?.customer_vehicle?.id
      if(item.quantity > 0 || item.quantity < 0) {
        previewPanel.value.materials.push({ ...item })
        openTab.value = 3
        item.quantity = null
      }
    }

    hasUpdated.value = 1;
  }
});

</script>
<template>
  <div class="border-b border-gray-200 dark:border-gray-700 pb-5">
    <!-- <div class="w-full mb-6">
        <div class="flex justify-between mb-3 bg-gray-200 py-3 px-2 rounded-md shadow-md">
            <h2 class="text-md font-semibold text-gray-500 dark:text-white">Entry Date: {{ moment(creditSale?.created_at).format("DD/MM/YYYY") }}</h2>
            <h2 class="text-md font-semibold text-gray-500 dark:text-white">Time: {{ moment(creditSale?.created_at).format("hh:mm A") }}</h2>
            <h2 class="text-md font-semibold text-gray-500 dark:text-white">{{ shift }} SHIFT</h2>
            <h2 class="text-md font-semibold text-gray-500 dark:text-white">{{  username  }}</h2>
        </div>
    </div> -->

    <div v-if="creditInfo" class="font-bold mt-3 mb-6 flex justify-evenly border border-gray-600 p-2 rounded-md">
      <span>Credit Limit: {{ numberFormat(creditSale?.customer?.credit_limit) }}</span>
      <span>Total Sale: {{ numberFormat(creditInfo.totalSale) }}</span>
      <span>Total Received: {{ numberFormat(creditInfo.totalReceived) }}</span>
      <span class="text-red-600">Total Due: {{ numberFormat(creditInfo.totalSale - creditInfo.totalReceived) }}</span>
    </div>
    <div class="w-full mb-5">
      <div class="grid grid-cols-4 gap-4">
        <label for="">
          <h2 class="text-md font-semibold dark:text-white">Sale Date</h2>
          <input type="date" readonly v-model="creditSale.date" class="bg-gray-100 block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        </label>
        <label for="">
          <h2 class="text-md font-semibold dark:text-white">Customer Code <span class="required-style">*</span></h2>
          <v-select :options="customers" placeholder="--Choose an option--" @search="fetchCustomer"  v-model="creditSale.customer" label="code" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>

          <input type="hidden" required v-model="creditSale.customer_id" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        </label>
        <label for="">
          <h2 class="text-md font-semibold dark:text-white">Customer Name</h2>
          <input type="text" readonly v-model="creditSale.customer_name" class="bg-gray-100 block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        </label>
        <label for="">
          <h2 class="text-md font-semibold dark:text-white">Slip No. <span class="required-style">*</span></h2>
          <input type="text" required v-model="creditSale.slip_no" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        </label>

        <!-- <label for="">
            <h2 class="text-md font-semibold dark:text-white">Payment Method</h2>
            <v-select :options="paymentMethods" placeholder="--Choose an option--" v-model="creditSale.payment_method" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
        </label>

        <label for="">
            <h2 class="text-md font-semibold dark:text-white">Billing Address</h2>
            <input type="text" v-model="creditSale.billing_address" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        </label>
        <label for="">
            <h2 class="text-md font-semibold dark:text-white">Billing Email</h2>
            <input type="text" v-model="creditSale.billing_email" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        </label> -->
      </div>
    </div>
    <div class="w-full flex">
      <div class="w-8/12 p-3 shadow-lg rounded-md border border-gray-400">

        <table class="w-full whitespace-no-wrap">
          <thead v-once>
          <tr>
            <th colspan="8" class="py-2 text-center !text-lg"><strong>FUEL &amp; LUBRICANTS</strong></th>
          </tr>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-2 py-1 w-44">Name </th>
            <th class="px-2 py-1 w-44">Vehicle No </th>
            <th class="px-2 py-1 w-28">In Stock</th>
            <th class="px-2 py-1 w-24">Unit</th>
            <th class="px-1 py-1 w-36">Rate</th>
            <th class="px-2 py-1 !w-16">Qty.</th>
            <th class="px-2 py-1 w-36">Amount</th>
          </tr>
          </thead>
          <tbody class=" divide-y dark:divide-gray-700 dark:bg-gray-800">
          <template v-for="(fuel,index) in creditSale.fuel" :key="index">
            <tr class="text-gray-700 dark:text-gray-400 text-center"  v-bind:class="{'hidden': openTab !== index, '': openTab === index}">
              <td class="py-1 text-sm" v-if="index < 3">
                <input type="text" v-model="creditSale.fuel[index].name" readonly class="bg-gray-100 label-item-input" name=" " :id="' '" />
              </td>
              <td class="py-1 text-sm" v-if="index == 3">
                <v-select :options="materials" placeholder="--Choose an option--" @search="fetchMobil"  v-model="creditSale.fuel[index].material" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                <input type="hidden" v-model="creditSale.fuel[index].material_id" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
              </td>
              <td class="py-1 text-sm">
                <v-select :options="customerVehicles" placeholder="--Choose an option--" @search="fetchVehicle"  v-model="creditSale.fuel[index].vehicle" label="vehicle_number" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>

                <input type="hidden" v-model="creditSale.fuel[index].customer_vehicle_id" class="label-item-input" name=" " :id="' '" />
              </td>
              <td class="py-1 text-sm">
                <input type="text" v-model="creditSale.fuel[index].in_stock" readonly class="bg-gray-100 label-item-input" name=" " :id="' '" />
              </td>
              <td class="py-1 text-sm">
                <input type="text" v-model="creditSale.fuel[index].unit" readonly class="bg-gray-100 label-item-input" name=" " :id="' '" />
              </td>
              <td class="py-1 text-sm w-16">
                <div v-if="formType=='edit'">
                  <input type="number" min="0" @keyup="calculatedAmount()" @change="calculatedAmount()" v-model="creditSale.fuel[index].unit_price" class="label-item-input" name=" " :id="' '" />
                </div>
                <div v-else>
                  <input type="number" min="0" readonly @keyup="calculatedAmount()" @change="calculatedAmount()" v-model="creditSale.fuel[index].unit_price" class="bg-gray-100 label-item-input" name=" " :id="' '" />
                </div>
              </td>
              <td class="py-1 text-sm w-1/6">
                <input type="number" @keyup="calculatedAmount()" @change="calculatedAmount()" v-model="creditSale.fuel[index].quantity" class="label-item-input" name=" " :id="index" />
              </td>
              <td class="py-1 text-sm">
                <input type="text" readonly v-model="creditSale.fuel[index].amount" class="bg-gray-100 label-item-input" name=" " :id="' '" />
              </td>
            </tr>

          </template>


          </tbody>
        </table>

      </div>

      <div class="flex justify-end mt-6 ml-2">
        <input type="hidden" name=" " :id="' '" v-model="creditSale.total_amount" >
      </div>
      <div class="w-4/12 ml-3 p-3 shadow-lg rounded-md border border-gray-400">

        <div v-if="previewPanel.materials.length > 0">
          <h4 class="text-md text-center my-2 font-semibold">Fuel &amp; Lubricants</h4>
          <table class="w-full text-sm">
            <thead>
            <tr>
              <td class="text-sm text-center">Item</td>
              <td class="w-12 text-sm text-center">Qty.</td>
              <td class="w-12 text-sm text-center">Unit Price</td>
              <td class="w-20 text-sm text-center">Amount</td>
              <td class="w-12 text-sm text-center">Action</td>
            </tr>
            </thead>
            <tr v-for="(fuel,index) in previewPanel.materials" :key="index">
              <td>{{ fuel.name ?? fuel.material.name }}</td>
              <td>{{ fuel.quantity }}</td>
              <td>{{ numberFormat(fuel.unit_price) }}</td>
              <td class="!text-right">{{ numberFormat(fuel.amount) }}</td>
              <td>
                <button @click="removeFuelPreviewPanel(index)" type="button" class="my-2 mx-auto flex items-center justify-center text-sm leading-5 text-white transition-colors duration-150 bg-red-500 border border-transparent rounded-full active:bg-[#0F6B90] hover:bg-red-800 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </button>
              </td>
            </tr>
            <tr>
              <td colspan="3" class="!text-right py-3"><strong>Grand Total</strong></td>
              <td class="!text-right py-3">{{ numberFormat(grandTotal) }}</td>
              <td></td>
            </tr>
          </table>
        </div>


        <div class="flex justify-center" v-if="previewPanel.materials.length > 0">

          <button @click="saveCreditSale()" type="button" class="my-2 flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <span v-if="formType == 'create'">Save &amp; New</span>
            <span v-else>Update</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<style lang="postcss" scoped>
.input-group {
  @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
}
.label-group {
  @apply block w-full mt-3 text-sm font-semibold;
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
.vs__selected{
  display: none !important;
}
.required-style{
  @apply text-red-400 font-semibold
}

th {
  @apply text-xs
}
tr,td,th {
  @apply border
}

</style>