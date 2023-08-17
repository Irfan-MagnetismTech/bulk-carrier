<template>
    <!-- Basic information -->
    <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Booking Date <span class="text-red-500">*</span></span>
            <input type="date" v-model="form.booking_date" required placeholder="Enter Client Email" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        </label>
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Client Name <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.client_name" required placeholder="Enter Client Name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        </label>
<!--        <label class="block w-full mt-3 text-sm">-->
<!--            <span class="text-gray-700 dark:text-gray-300">Ordered Tues</span>-->
<!--            <input type="number" v-model="form.ordered_tues" required placeholder="Enter Ordered Tues" min="1" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />-->
<!--        </label>-->
    </div>
    <!-- Client infromation -->
    <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Client Email <span class="text-red-500">*</span></span>
            <input type="email" v-model="form.client_email" placeholder="Enter Client Email" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        </label>
        <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Client Contact <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.client_contact" required placeholder="Enter Contact Number" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        </label>
    </div>
    <!-- Port information -->
    <div class="input-group flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
      <label class="label-group" for="portOrigin">
        <span class="label-item-title">Departure Port <span class="text-red-500">*</span></span>
        <v-select v-model="form.departure_port_code" id="portOrigin" @search="fetchOptions" value="id" :options="portName" :reduce="portName => portName.code" label="code_name" placeholder="Enter Port Code or Name" class="mt-1 placeholder-gray-600"></v-select>
      </label>
      <label class="label-group">
        <span class="label-item-title">Destination Port <span class="text-red-500">*</span></span>
        <v-select v-model="form.destination_port_code" @search="fetchOptions" value="id" :options="portName" :reduce="portName => portName.code" label="code_name" placeholder="Enter Port Code or Name" class="mt-1"></v-select>
      </label>
    </div>
    <!-- Terms -->
    <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Shipping / Payment Terms <span class="text-red-500">*</span></span>
            <select v-model="form.shipment_term" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                <option value>-- Select Shipment Term --</option>
                <option v-for="(shipmentTermOption, index) in shipmentTermOptions" :key="index">{{ shipmentTermOption }}</option>
            </select>
        </label>
        <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Shipping Mode <span class="text-red-500">*</span></span>
            <select v-model="form.payment_term" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                <option value>-- Select Payment Term --</option>
                <option v-for="(paymentTermOption, index) in paymentTermOptions" :key="index">{{ paymentTermOption }}</option>
            </select>
        </label>
    </div>
    <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Commodity </span>
        <textarea v-model="form.commodity" placeholder="Enter Commodity" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></textarea>
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Note</span>
        <textarea v-model="form.note" placeholder="Enter Note" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></textarea>
      </label>
    </div>
    <!-- Cosignee information -->
    <fieldset class="px-4 pb-4 mt-3 border rounded bord2er-gray-700 dark:border-gray-400">
        <legend class="text-gray-700 dark:text-gray-300">Consignee Information <span class="text-red-500">*</span></legend>
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
            <label class="block w-full mt-3 text-sm">
                <span class="text-gray-700 dark:text-gray-300">Company <span class="text-red-500">*</span></span>
                <input type="text" required v-model="form.consignee_company" placeholder="Enter Company Name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            </label>

            <label class="block w-full mt-3 text-sm">
                <span class="text-gray-700 dark:text-gray-300">Contact Person Name <span class="text-red-500">*</span></span>
                <input type="text" required v-model="form.consignee_contact_name" placeholder="Enter Contact Person Name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            </label>
        </div>
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
            <label class="block w-full mt-3 text-sm">
                <span class="text-gray-700 dark:text-gray-300">Email <span class="text-red-500">*</span></span>
                <input type="email" required v-model="form.consignee_email" placeholder="Enter Consignee Email" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            </label>
            <label class="block w-full mt-3 text-sm">
                <span class="text-gray-700 dark:text-gray-300">Contact <span class="text-red-500">*</span></span>
                <input type="text" required v-model="form.consignee_contact" placeholder="Enter Consignee Contact" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            </label>
        </div>
    </fieldset>

  <fieldset class="px-4 pb-4 mt-3 border rounded bord2er-gray-700 dark:border-gray-400">
    <legend class="text-gray-700 dark:text-gray-300"> Booked Container Details </legend>
    <table id="table" class="table w-full whitespace-no-wrap">
      <thead>
      <tr class="text-xs tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="px-4 py-3" rowspan="2">Status</th>
        <th class="px-4 py-3" colspan="3">1 Tue </th>
        <th class="px-4 py-3" colspan="3">2 Tues</th>
        <th class="px-4 py-3" colspan="3">2.5 Tues</th>
        <th class="px-4 py-3" rowspan="2">TTLTues</th>
      </tr>
      <tr class="text-xs tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="px-4 py-3">20GP </th>
        <th class="px-4 py-3">20RF</th>
        <th class="px-4 py-3">20FR</th>
        <th class="px-4 py-3">40GP </th>
        <th class="px-4 py-3">40RF</th>
        <th class="px-4 py-3">40FR</th>
        <th class="px-4 py-3">45GP </th>
        <th class="px-4 py-3">45RF</th>
        <th class="px-4 py-3">45FR</th>
      </tr>
      </thead>
      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(booking_tue,index) in form.booking_tues" :key="index">
        <td class="px-1 py-1">
          <table>
            <tr>
              <td class="md:w-2/6" style="border: 0px"><input type="text" v-model="form.booking_tues[index].status" readonly name="port_code" placeholder="Laden" class="block bg-gray-200 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
            </tr>
          </table>
        </td>
        <td class="px-1 py-1">
          <table>
            <tr>
              <td class="md:w-2/6" style="border: 0px"><input type="number" v-model="form.booking_tues[index].gp_20" placeholder="20GP" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
            </tr>
          </table>
        </td>
        <td class="px-1 py-1">
          <table>
            <tr>
              <td class="md:w-2/6" style="border: 0px"><input type="number" v-model="form.booking_tues[index].rf_20" placeholder="20RF" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
            </tr>
          </table>
        </td>
        <td class="px-1 py-1">
          <table>
            <tr>
              <td class="md:w-2/6" style="border: 0px"><input type="number" v-model="form.booking_tues[index].fr_20" placeholder="20FR" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
            </tr>
          </table>
        </td>
        <td class="px-1 py-1">
          <table>
            <tr>
              <td class="md:w-2/6" style="border: 0px"><input type="number" v-model="form.booking_tues[index].gp_40" placeholder="40GP" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
            </tr>
          </table>
        </td>
        <td class="px-1 py-1">
          <table>
            <tr>
              <td class="md:w-2/6" style="border: 0px"><input type="number" v-model="form.booking_tues[index].rf_40" placeholder="40RF" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
            </tr>
          </table>
        </td>
        <td class="px-1 py-1">
          <table>
            <tr>
              <td class="md:w-2/6" style="border: 0px"><input type="number" v-model="form.booking_tues[index].fr_40" placeholder="40FR" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
            </tr>
          </table>
        </td>
        <td class="px-1 py-1">
          <table>
            <tr>
              <td class="md:w-2/6" style="border: 0px"><input type="number" v-model="form.booking_tues[index].gp_45" placeholder="45GP" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
            </tr>
          </table>
        </td>
        <td class="px-1 py-1">
          <table>
            <tr>
              <td class="md:w-2/6" style="border: 0px"><input type="number" v-model="form.booking_tues[index].rf_45" placeholder="45RF" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
            </tr>
          </table>
        </td>
        <td class="px-1 py-1">
          <table>
            <tr>
              <td class="md:w-2/6" style="border: 0px"><input type="number" v-model="form.booking_tues[index].fr_45" placeholder="45FR" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
            </tr>
          </table>
        </td>
        <td class="px-1 py-1">
          <table>
            <tr>
              <td class="md:w-2/6" style="border: 0px"><input type="number" step=".01" v-model="form.booking_tues[index].total" placeholder="Tues" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr class="text-gray-700 dark:text-gray-400">
        <td class="px-1 py-1" colspan="9" style="border-right: 1px solid #fff"></td>
        <td class="px-1 py-1">Total Tues</td>
        <td class="px-1 py-1">
          <table>
            <tr>
              <td class="md:w-2/6" style="border: 0px"><input type="number" step=".01" readonly v-model="form.ordered_tues" placeholder="Tues" class="block w-full mt-1 text-xs rounded dark:text-gray-300 bg-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" /></td>
            </tr>
          </table>
        </td>
      </tr>
      </tbody>
    </table>
  </fieldset>
</template>
<script setup>
import usePort from "../../composables/usePort";
import {ref, watch, watchEffect} from "vue";
const props = defineProps({
    form: {
        required: false,
        default: {}
    },
    page: {
      required: false,
      default: {}
    },
});


const { ports, portName, getPortsByNameOrCode } = usePort();
const portDischarge = ref(null);
const portOrigin = ref(null);

function fetchOptions(search, loading) {
  getPortsByNameOrCode(search);
}

// watch(portDischarge, (value) => {
//   if (value) {
//     props.form.destination_port_code = value.code;
//   }
// });
//
// watch(portOrigin, (value) => {
//   if (value) {
//     props.form.departure_port_code = value.code;
//   }
// });

// watch(props.form.booking_tues, (value) => {
//   console.log("sdfsf");
//   let total = 0;
//   let total_tue = 0;
//   props?.form?.booking_tues.forEach((tue, index) => {
//     let gp_20 = props.form.booking_tues[index].gp_20 ?? 0;
//     let rf_20 = props.form.booking_tues[index].rf_20 ?? 0;
//     let fr_20 = props.form.booking_tues[index].fr_20 ?? 0;
//     let gp_40 = props.form.booking_tues[index].gp_40 ?? 0;
//     let rf_40 = props.form.booking_tues[index].rf_40 ?? 0;
//     let fr_40 = props.form.booking_tues[index].fr_40 ?? 0;
//     let gp_45 = props.form.booking_tues[index].gp_45 ?? 0;
//     let rf_45 = props.form.booking_tues[index].rf_45 ?? 0;
//     let fr_45 = props.form.booking_tues[index].fr_45 ?? 0;
//     let total_tue = parseFloat(gp_20 + rf_20 + fr_20 + gp_40 + rf_40 + fr_40 + gp_45 + rf_45 + fr_45) ?? 0;
//
//     props.form.booking_tues[index].total = (total_tue).toFixed(2) ?? 0;
//     total += parseFloat(props.form.booking_tues[index].total);
//   });
//   props.form.ordered_tues = total.toFixed(2) ?? 0;
//   props.form.total_tues = total.toFixed(2) ?? 0;
// });


  watchEffect(() => {
    if(props?.form?.booking_tues?.length) {
      if(props.page === "create"){
        let total = 0;
        let total_tue = 0;
        props.form.booking_tues.forEach((tue, index) => {
          let gp_20 = props.form.booking_tues[index].gp_20 ?? 1;
          let rf_20 = props.form.booking_tues[index].rf_20 ?? 1;
          let fr_20 = props.form.booking_tues[index].fr_20 ?? 1;
          let gp_40 = (props.form.booking_tues[index].gp_40 * 2) ?? 1;
          let rf_40 = (props.form.booking_tues[index].rf_40 * 2) ?? 1;
          let fr_40 = (props.form.booking_tues[index].fr_40 * 2) ?? 1;
          let gp_45 = (props.form.booking_tues[index].gp_45 * 2.5) ?? 1;
          let rf_45 = (props.form.booking_tues[index].rf_45 * 2.5) ?? 1;
          let fr_45 = (props.form.booking_tues[index].fr_45 * 2.5) ?? 1;
          let total_tue = parseFloat(gp_20 + rf_20 + fr_20 + gp_40 + rf_40 + fr_40 + gp_45 + rf_45 + fr_45) ?? 0;

          props.form.booking_tues[index].total = (total_tue).toFixed(2) ?? 0;
          total += parseFloat(props.form.booking_tues[index].total);
        });
        props.form.ordered_tues = total.toFixed(2) ?? 0;
        props.form.total_tues = total.toFixed(2) ?? 0;
      } else{
        console.log(props.page);
      }
    }
  });

const shipmentTermOptions = ['Prepaid', 'Collect', 'Prepaid & Collect'];
const paymentTermOptions = ['FI-FO', 'FI-HK', 'FI-CY', 'HK-FO', 'HK-HK', 'HK-CY', 'CY-CY']; 
</script>

<style lang="postcss" scoped>
  #table, #table th, #table td{
    @apply border border-collapse border-gray-400 text-center text-gray-700 font-medium;
  }

  #table th{
    @apply bg-gray-200 dark:bg-gray-700;
  }

.input-group {
  @apply flex flex-col items-center justify-center w-full md:flex-row md:gap-2;
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