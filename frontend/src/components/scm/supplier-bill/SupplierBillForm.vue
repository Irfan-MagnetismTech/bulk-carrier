<script setup>
    import { ref, watch, onMounted, computed } from 'vue';
    import Error from "../../Error.vue";
    import useVendor from "../../../composables/configuration/useVendor";
    import useMaterialReceive from "../../../composables/scm/useMaterialReceive";
    import useHelper from "../../../composables/useHelper";
    import DatePicker from 'vue3-datepicker';
    import moment from 'moment';

    const { datePickerFormat, numberFormat } = useHelper();
    const { vendors, getVendors } = useVendor();
    const { materialReceives, searchMaterialReceive } = useMaterialReceive();

    const props = defineProps({
      form: { type: Object, required: true },
      page: { type: String, required: true },
      errors: { type: [Object, Array], required: false },
      defaultVendor: { type: Object, required: true },
    });

    function fetchMaterialReceive(search, loading) {
      searchMaterialReceive(search, loading);
      loading(true)
    }

    onMounted(() => {
      getVendors();
      // props.form.page = 'create';
    });

    watch(() => props.defaultVendor, (value) => {
        setTimeout(function() {
          props.form.vendor_id = value?.id
        }, 0)
    }, { deep: true })

    let selectedDate = ref(null);
    const textInputOptions = ref({
      format: 'dd.MM.yyyy'
    })

    watch(() => selectedDate.value, (value) => {
      props.form.date = datePickerFormat(value);
    });

    watch(() => props.form.mrrs, (mrrs) => {
      let total = 0;
      for (let index in mrrs) {
          let item = mrrs[index];
          if(item.material_receive != '') {
            // let mrrInfo = materialReceives.value.find((mrrInfo) => mrrInfo.id === item.material_receive_id);
            item.po_no = item?.material_receive?.purchase_order?.sequence
            item.purchase_order_id = item?.material_receive?.purchase_order?.id
            item.material_receive_id = item?.material_receive?.id
            item.vendor_id = props.form.vendor_id
            item.amount = item?.material_receive?.stockable_sum_amount
            // alert(item.amount)

            total += parseFloat(item.amount) || 0
          }
          
        }

      props.form.sub_total = total;

      props.form.final_total = total - (parseFloat(props.form.discount) || 0)

    }, { deep: true })

    watch(() => props.form.discount, (discount) => {
      props.form.final_total = props.form.sub_total - (parseFloat(props.form.discount) || 0)
    }, { deep : true })

    const addMRR = () => {
      props.form.mrrs.push({
                material_receive_id: null,
                amount: null,
                purchase_order_id: null,
                vendor_id: null,
                po_no: null,
            })
    }

    const removeMaterialReceive = (index) => {
      if(props.form.mrrs.length>1) {
            props.form.mrrs.splice(index, 1);
        }
    }


</script>
<template>
  
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm md:w-4/6">
      <span class="text-gray-700 dark:text-gray-300">Date <span class="text-red-500">*</span></span>
      <DatePicker v-model="selectedDate" required placeholder="dd-mm-yyyy" class="block w-full datepicker custom-date-input" :typeable="true" lang="fr" inputFormat="dd-MM-yyyy" :text-input-options="textInputOptions" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Supplier <span class="text-red-500">*</span></span>
      <v-select :options="vendors" placeholder="--Choose an option--" v-model="form.vendor_id" label="name" :reduce="vendors => vendors.id" class="block w-full rounded form-input">
        <template #search="{attributes, events}">
          <input class="vs__search w-full" :required="!form.vendor_id" v-bind="attributes" v-on="events"/>
        </template>
      </v-select>
      <Error v-if="errors?.vendor_id" :errors="errors.vendor_id" />
    </label>
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Bill No <span class="text-red-500">*</span></span>
      <input type="text" required v-model="form.bill_no" class="block w-full form-input">
      <Error v-if="errors?.bill_no" :errors="errors.bill_no" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Remarks</span>
      <input type="text" v-model="form.remarks" class="block w-full form-input">
      <Error v-if="errors?.remarks" :errors="errors.remarks" />
    </label>
    
  </div>
  
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Bill Details <span class="text-red-500">*</span></legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs text-center text-gray-500 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="px-4 py-3 align-center">MRR</th>
        <th class="px-4 py-3 align-center">PO No.</th>
        <th class="px-4 py-3 align-center">Amount</th>
        <th class="px-4 w-24 align-center">
          <button type="button" @click="addMRR(index)" :disabled="isLoading" class="mx-auto flex items-center justify-center px-4 py-2 text-sm text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg active:bg-[#0F6B90] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">+</button>
        </th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-gray-700 dark:text-gray-400" v-for="(mrr, index) in form.mrrs" :key="index">
            <td class="">
              
              <v-select :options="materialReceives" placeholder="--Choose an option--" @search="fetchMaterialReceive" v-model="form.mrrs[index].material_receive" label="challan_no" class="block w-full rounded form-input p-2">
              </v-select>

              <input type="hidden" name="" v-model="form.mrrs[index].material_receive_id">

            </td>
            <td class="w-64 px-2">
              <input type="text" readonly v-model="form.mrrs[index].po_no" class="bg-gray-100 block w-full form-input">
            </td>
            <td class="w-64 px-2">
              <input type="text" readonly v-model="form.mrrs[index].amount" class="bg-gray-100 block w-full form-input">
            </td>
            <td>
              <button type="button" @click="removeMaterialReceive(index)" :disabled="isLoading" class="mx-auto flex items-center justify-center px-4 py-2 mt-2 text-sm leading-5 text-white transition-colors duration-150 bg-red-500 border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B90] hover:bg-red-800 focus:outline-none focus:shadow-outline-purple">-</button>

            </td>
          
          </tr>
          <tr>
            <td colspan="4" class="h-10"></td>
          </tr>
        <tr>
          <td colspan="2" class="!text-right pr-2 font-semibold">Sub Total</td>
          <td>
            
            <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2">
                {{ numberFormat(form.sub_total) }}
            </span>
          </td>
        <td></td>

        </tr>
      <tr>
        <td colspan="2" class="!text-right pr-2 font-semibold">Discount</td>
        <td>
            <input type="number" v-model="form.discount" class="block w-full form-input !text-right pr-2">
        </td>
        <td></td>

      </tr>
      
      <tr>
        <td colspan="2" class="!text-right pr-2 font-semibold">Grand Total</td>
        <td>
          <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                {{ numberFormat(form.final_total) }}
            </span>
        </td>
        <td></td>
      </tr>
          
      
      
      </tbody>
    </table>
  </fieldset>

</template>
<style lang="postcss" scoped>
    .input-group {
        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
    }
    .label-group {
        @apply block w-full mt-3 text-sm font-semibold;
    }
    .label-item-title {
        @apply text-gray-700 dark:text-gray-300 text-sm;
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

    table tr,td,th {
        @apply border border-gray-300
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