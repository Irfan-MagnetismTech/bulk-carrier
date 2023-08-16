<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";
    import useMaterial from "../../../composables/scm/useMaterial.js";
    import useVendor from "../../../composables/configuration/useVendor";
    import useMaterialMovementRequisition from '../../../composables/scm/useMaterialMovementRequisition';

    const { vendors, getVendors } = useVendor();
    const { material, materials, getMaterials } = useMaterial();
    const { materialMovementRequisitions, searchMaterialMovementRequisition, getPendingMaterialMovementRequisitions } = useMaterialMovementRequisition();

    const props = defineProps({
      form: { type: Object, required: true },
      errors: { type: [Object, Array], required: false },
      movementType: { type: String },
      formType: { type: String }
    });

    function fetchMovementRequisition(search, loading) {
      loading(true);
      searchMaterialMovementRequisition(search, loading)
    }

    function addMaterial() {
      let obj = {
        material_id: '',
        size: '',
        unit: '',
        quantity: null,
      };
      props.form.materials.push(obj);
    }

    function removeMaterial(index){
      props.form.materials.splice(index, 1);
    }

    const requisitionWarehouseInfo = ref('');
    const hasUpdated = ref(0);
    watch(() => props.form.material_movement_requisition_id, (value) => {

        console.log("value", value)
        const requisitionInfo = materialMovementRequisitions.value.find((requisition) => requisition.id === value);

        if(props.form.id && hasUpdated.value == 0) {
            props.form.from_warehouse_id = props.form?.movement_requisition?.from_warehouse_id;
            props.form.to_warehouse_id = props.form?.movement_requisition?.to_warehouse_id;
            props.form.materials = props.form?.stockable;
            requisitionWarehouseInfo.value = ''
            hasUpdated.value = 1;
        }else if(requisitionInfo?.stockable) {
          props.form.from_warehouse_id = requisitionInfo.from_warehouse_id;
          props.form.to_warehouse_id = requisitionInfo.to_warehouse_id;
          props.form.materials = requisitionInfo.stockable;

          requisitionWarehouseInfo.value = "Movement From "+requisitionInfo?.from_warehouse?.name+" to "+requisitionInfo?.to_warehouse?.name
        } else {
          requisitionWarehouseInfo.value = '';
        }


        console.log("request info", requisitionInfo)
      
    }, { deep: true })

    onMounted(() => {
      props.form.movement_type = (props.movementType == 'movementOut') ? 'out' : 'in';
      
      getMaterials();
      getPendingMaterialMovementRequisitions();

      
    });

</script>
<template>

  <div class="text-center bg-gray-100 p-2 rounded-md shadow-md" v-if="requisitionWarehouseInfo != ''">
    {{ requisitionWarehouseInfo }}
  </div>
  <!-- Basic information -->
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-3 text-sm md:w-2/6">
        <span class="text-gray-700 dark:text-gray-300">Movement Requisition <span class="text-red-500">*</span></span>
        <v-select :options="materialMovementRequisitions" placeholder="--Choose an option--" @search="fetchMovementRequisition" v-model="form.material_movement_requisition_id" label="movement_requisition_no" :reduce="materialMovementRequisition => materialMovementRequisition.id" class="block form-input">
            <template #search="{attributes, events}">
                <input
                    class="vs__search"
                    :required="!form.material_movement_requisition_id"
                    v-bind="attributes"
                    v-on="events"
                    />
            </template>
        </v-select>
      </label>
    <!-- </label>
      <span class="text-gray-700 dark:text-gray-300">Request Date <span class="text-red-500">*</span></span>
      <input type="date" v-model="form.request_date" required class="block w-full form-input" />
      <Error v-if="errors?.request_date" :errors="errors.request_date" />
    </label> -->
    <label class="block w-full mt-3 text-sm md:w-1/6">
      <span class="text-gray-700 dark:text-gray-300">Movement Date <span class="text-red-500">*</span></span>
      <input type="date" v-model="form.movement_date" required class="block w-full form-input" />
      <Error v-if="errors?.movement_date" :errors="errors.movement_date" />
    </label>

    <label class="block w-full mt-3 text-sm md:w-3/6">
      <span class="text-gray-700 dark:text-gray-300">Remarks</span>
      <input type="text" v-model="form.remarks" class="block w-full form-input" />
      <Error v-if="errors?.remarks" :errors="errors.remarks" />
    </label>
  </div>

  <!-- CS Materials -->
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Materials <span class="text-red-500">*</span></legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="px-4 py-3 align-bottom">Material</th>
        <th class="px-4 py-3 align-bottom">Size</th>
        <th class="px-4 py-3 align-bottom">Unit</th>
        <th class="px-4 py-3 align-bottom">Quantity</th>
        <th class="px-4 py-3 text-center align-bottom">Action</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(material, index) in form.materials" :key="index">
        <td class="w-1/6">
          <select v-model="form.materials[index].material_id" :disabled="true" class="!text-black block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <option value="" disabled selected>Select</option>
            <option :value="material.id" v-for="(material,index) in materials">{{ material.name }}</option>
          </select>
        </td>
        <td>
          <input type="text" v-model="form.materials[index].size" class="block w-full form-input">
        </td>
        <td>
          <input type="text" readonly v-model="form.materials[index].unit" class="vms-readonly-input block w-full form-input">
        </td>
        <td>
          <input type="text" readonly v-model="form.materials[index].quantity" class="vms-readonly-input block w-full form-input">
        </td>
        
        <td class="px-1 py-1 text-center">
          <button type="button" @click="removeMaterial(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
          </button>
        </td>
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

    .readonly-select .vs__open-indicator {
      display: none;
    }
    
    .readonly-select .vs__dropdown-toggle {
      pointer-events: none;
    }
    

</style>