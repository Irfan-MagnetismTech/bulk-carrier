<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";
    import useMaterial from "../../../composables/scm/useMaterial.js";
    import useVendor from "../../../composables/configuration/useVendor";
    import usePurchaseOrder from "../../../composables/scm/usePurchaseOrder";
    import useWarehouse from "../../../composables/configuration/useWarehouse";
    import useHelper from "../../../composables/useHelper";
    import DatePicker from 'vue3-datepicker';

    const { datePickerFormat } = useHelper();
    const { vendors, getVendors } = useVendor();
    const { material, materials, getMaterials } = useMaterial();
    const { filteredPurchaseOrders, getAllPendingPurchaseOrders, poReceivedMaterials, getPurchaseOrderReceivedMaterial } = usePurchaseOrder();
    const { warehouses, getWarehouses } = useWarehouse();

    const props = defineProps({
      form: { type: Object, required: true },
      page: { type: String, required: true },
      errors: { type: [Object, Array], required: false },
      defaultVendor: { type: Object, required: true },
    });

    // function addMaterial() {
    //   let obj = {
    //     material_id: '',
    //     material_category_id: '',
    //     size: '',
    //     unit: '',
    //     quantity: 0.0,
    //     unit_price: 0.0,
    //     amount: 0.0,
    //   };
    //   props.form.materials.push(obj);
    // }

    function removeMaterial(index){
      props.form.stockable.splice(index, 1);
    }

    //watch form.purchase_order_id and find purchase order from purchaseOrders where id = form.purchase_order_id
    watch(() => props.form?.purchase_order_id, (newVal, oldVal) => {
      let formData = {
        purchase_order_id: newVal,
      }
      getPurchaseOrderReceivedMaterial(formData);
    }, { deep: true });

    const hasUpdated = ref(0);
    watch(() => props.form?.warehouse_id, (value) => {

      if(props.form?.stockable) {
        if(props.form?.id && hasUpdated.value == 0) {
          hasUpdated.value = 1
          filteredPurchaseOrders.value = [props.form.purchase_order]
        } else {
          for(let index in props.form.stockable) {
            props.form.stockable[index].warehouse_id = value
          }
        }
      }
    }, { deep: true });


    //watch poReceivedMaterials
    watch(() => poReceivedMaterials.value, (newVal, oldVal) => {
      if(props.form?.page !== 'show'){
        props.form.stockable = [];
        props.form.stockable = newVal;
      }

      if(props.form.stockable && poReceivedMaterials.value && props.form?.id) {
        for(let index in props.form.stockable) {
          props.form.stockable[index].po_quantity = poReceivedMaterials.value.find(({ material_id }) => material_id === props.form.stockable[index].material_id).po_quantity
          // props.form.stockable[index].total_received_quantity = props.form.stockable[index].quantity
        }
      }

    });

    onMounted(() => {
      getMaterials(1,0);
      getVendors();
      getWarehouses('Warehouse');
      props.form.page = 'create';
      getAllPendingPurchaseOrders();

    });

    watch(() => props.defaultVendor, (value) => {
        setTimeout(function() {
          props.form.supplier_id = value?.id
        }, 0)
    }, { deep: true })

    let selectedDate = ref(null);
    const textInputOptions = ref({
      format: 'dd.MM.yyyy'
    })

    watch(() => selectedDate.value, (value) => {
      props.form.date = datePickerFormat(value);
    });

</script>
<template>
  <!-- Basic information -->
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm md:w-4/6">
      <span class="text-gray-700 dark:text-gray-300">Date <span class="text-red-500">*</span></span>
<!--      <DatePicker v-model="selectedDate" placeholder="dd-mm-yyyy" class="block w-full datepicker custom-date-input" :typeable="true" lang="fr" inputFormat="dd-MM-yyyy" :text-input-options="textInputOptions" />-->
      <input type="date" v-model="form.date" class="w-full form-input">
    </label>
    <label class="block w-full mt-3 text-sm md:w-4/6">
      <span class="text-gray-700 dark:text-gray-300">Shift <span class="text-red-500">*</span></span>
      <select v-model="form.shift" class="block w-full form-input" required>
        <option value="" selected disabled>Select</option>
        <option value="A">A</option>
        <option value="B">B</option>
      </select>
      <Error v-if="errors?.shift" :errors="errors.shift" />
    </label>
    <label class="block w-full mt-3 text-sm md:w-4/6">
      <span class="text-gray-700 dark:text-gray-300">Purchase Order <span class="text-red-500">*</span></span>
      <v-select :options="filteredPurchaseOrders" placeholder="--Choose an option--" v-model="form.purchase_order_id" label="sequence" :reduce="filteredPurchaseOrders => filteredPurchaseOrders.id" class="block w-full rounded form-input">
        <template #search="{attributes, events}">
          <input class="vs__search w-full" :required="!form.purchase_order_id" v-bind="attributes" v-on="events"/>
        </template>
      </v-select>
      <Error v-if="errors?.purchase_order_id" :errors="errors.purchase_order_id" />
    </label>
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Supplier <span class="text-red-500">*</span></span>
      <v-select :options="vendors" placeholder="--Choose an option--" v-model="form.supplier_id" label="name" :reduce="vendors => vendors.id" class="block w-full rounded form-input">
        <template #search="{attributes, events}">
          <input class="vs__search w-full" :required="!form.supplier_id" v-bind="attributes" v-on="events"/>
        </template>
      </v-select>
      <Error v-if="errors?.supplier_id" :errors="errors.supplier_id" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Warehouse <span class="text-red-500">*</span></span>
      <v-select :options="warehouses" placeholder="--Choose an option--" v-model="form.warehouse_id" label="name" :reduce="warehouses => warehouses.id" class="block w-full rounded form-input">
        <template #search="{attributes, events}">
          <input class="vs__search w-full" :required="!form.warehouse_id" v-bind="attributes" v-on="events"/>
        </template>
      </v-select>
      <Error v-if="errors?.warehouse_id" :errors="errors.warehouse_id" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Challan No <span class="text-red-500">*</span></span>
      <input type="text" v-model="form.challan_no" required class="block w-full form-input" />
      <Error v-if="errors?.challan_no" :errors="errors.challan_no" />
    </label>
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Remarks</span>
      <textarea v-model="form.remarks" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></textarea>
      <Error v-if="errors?.remarks" :errors="errors.remarks" />
    </label>
    <label class="block w-full mt-3 text-sm"></label>
    <label class="block w-full mt-3 text-sm"></label>
  </div>

  <!-- CS Materials -->
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Materials <span class="text-red-500">*</span></legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs text-center text-gray-500 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th>Warehouse</th>
        <th class="px-4 py-3 align-bottom">Material</th>
<!--        <th class="px-4 py-3 align-bottom">Material Category</th>-->
        <th class="px-4 py-3 align-bottom w-12">Unit</th>
        <th class="px-4 py-3 align-bottom">Pack Size</th>
        <th class="px-4 py-3 align-bottom">PO Qty.</th>
        <th class="px-4 py-3 align-bottom"><nobr>Total Received Qty.</nobr></th>
        <th class="px-4 py-3 align-bottom">Delivered Qty.</th>
        <th class="px-4 py-3 align-bottom">Remarks</th>
        <th class="px-4 py-3 text-center align-bottom">Action</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr v-if="form.stockable?.length" class="text-gray-700 dark:text-gray-400" v-for="(material, index) in form.stockable" :key="index">
        <td class="w-1/6">
          <label class="block w-full mt-3 text-sm">
            <v-select :options="warehouses" placeholder="--Choose an option--" v-model="form.stockable[index].warehouse_id" label="name" :reduce="warehouses => warehouses.id" class="block w-full rounded form-input">
              <template #search="{attributes, events}">
                <input class="vs__search w-full" :required="!form.stockable[index].warehouse_id" v-bind="attributes" v-on="events"/>
              </template>
            </v-select>
          </label>
        </td>
        <td class="w-1/6">
          <select v-model="form.stockable[index].material_id" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <option value="" disabled selected>Select</option>
            <option :value="material.id" v-for="(material,index) in materials">{{ material.name }}</option>
          </select>
        </td>
<!--        <td>-->
<!--          <input type="text" v-model="form.materials[index].material_category_name" readonly class="vms-readonly-input block w-full form-input">-->
<!--        </td>-->
        <td>
          <input type="text" readonly v-model="form.stockable[index].unit" class="vms-readonly-input block w-full form-input">
        </td>
        <td>
          <input type="text" readonly v-model="form.stockable[index].size" class="vms-readonly-input block w-full form-input">
        </td>
        <td>
          <input type="text" readonly v-model="form.stockable[index].po_quantity" class="vms-readonly-input block w-full form-input">
        </td>
        <td>
          <input type="text" readonly v-model="form.stockable[index].total_received_quantity" class="vms-readonly-input block w-full form-input">
        </td>
        <td>
          <input type="text" v-model="form.stockable[index].quantity" class="block w-full form-input !text-right">
        </td>
        <td>
          <input type="text" v-model="form.stockable[index].remarks" class="block w-full form-input">
        </td>
        <td class="px-1 py-1 text-center">
          <button type="button" @click="removeMaterial(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
          </button>
<!--          <button v-else type="button" @click="addMaterial()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">-->
<!--            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">-->
<!--              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />-->
<!--            </svg>-->
<!--          </button>-->
        </td>
      </tr>
      <tr v-else class="text-gray-700 dark:text-gray-400">
        <td colspan="8" class="text-center p-2">No data found</td>
      </tr>
<!--      <tr class="text-gray-700 dark:text-gray-400" v-if="form.materials.length">-->
<!--        <td colspan="6" class="text-right font-bold p-2">Total Amount</td>-->
<!--        <td class="text-right">-->
<!--          <input type="text" readonly v-model="form.total_amount" class="vms-readonly-input block w-full form-input">-->
<!--        </td>-->
<!--        <td></td>-->
<!--      </tr>-->
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