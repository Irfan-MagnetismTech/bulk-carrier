<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Customer Details</h2>
    <default-button :title="'Customer List'" :to="{ name: 'ops.configurations.customers.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
    <div class="flex md:gap-4">
        <div class="w-full">
            <table class="w-full">
                <thead>
                    <tr>
                        <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="2">Basic Info</td>
                    </tr>
                </thead>
                <tbody>
                    <!-- <tr>
                        <th class="w-40">Business Unit</th>
                        <td><span :class="cargoType?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ cargoType?.business_unit }}</span></td>
                    </tr> -->
                    <tr>
                        <th class="w-40">Customer Code</th>
                        <td>{{  customer.code }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Customer Name</th>
                        <td>{{ customer?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Legal Name</th>
                        <td>{{ customer?.legal_name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Tax Identification</th>
                        <td>{{ customer?.tax_id }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Postal Address</th>
                        <td>{{ customer?.postal_address }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Post Code</th>
                        <td>{{ customer?.post_code }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">City</th>
                        <td>{{ customer?.city }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Country</th>
                        <td>{{ customer?.country }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Business License No.</th>
                        <td>{{ customer?.business_license_no }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">BIN/GST/SST No.</th>
                        <td>{{ customer?.bin_gst_sst_no }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="2">Contact Info</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th class="w-40">Phone</th>
                    <td>{{  customer.phone }}</td>
                </tr>
                <tr>
                    <th class="w-40">Company Reg. No</th>
                    <td>{{ customer?.company_reg_no }}</td>
                </tr>
                <tr>
                    <th class="w-40">Email <small>(General)</small></th>
                    <td>{{ customer?.email_general }}</td>
                </tr>
                <tr>
                    <th class="w-40">Email <small>(Agreement)</small></th>
                    <td>{{ customer?.email_agreement }}</td>
                </tr>
                <tr>
                    <th class="w-40">Email <small>(Invoice)</small></th>
                    <td>{{ customer?.email_invoice }}</td>
                </tr>
            </tbody>
          </table>
        </div>
    </div>
  </div>
</template>
<style lang="postcss" scoped>
    th, td, tr {
      @apply text-left border-gray-500
    }
    
    tfoot td{
      @apply text-center
    }
  
</style>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import CustomerForm from '../../../components/operations/configurations/CustomerForm.vue';
import useCustomer from '../../../composables/operations/useCustomer';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const customerId = route.params.customerId;
const { customer, showCustomer, updateCustomer, errors } = useCustomer();

const { setTitle } = Title();

setTitle('Customer Details');

onMounted(() => {
  showCustomer(customerId);
});
</script>