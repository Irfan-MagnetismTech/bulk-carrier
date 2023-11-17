<template>
     

    <h4 class="text-md font-semibold">Customer Info</h4>
    
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Customer Code <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.code" placeholder="Customer Code" class="form-input" required autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Legal Name <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.legal_name" placeholder="Legal Name" class="form-input" required autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Customer Name <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.name" placeholder="Customer Name" class="form-input" required autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Tax Identification</span>
            <input type="text" v-model.trim="form.tax_id" placeholder="Tax Identification" class="form-input" autocomplete="off" />
        </label>
        
    </div>
    
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Postal Address</span>
            <input type="text" v-model.trim="form.postal_address" placeholder="Postal Address" class="form-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Post Code</span>
          <input type="text" v-model.trim="form.post_code" placeholder="Post Code" class="form-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">City</span>
            <input type="text" v-model.trim="form.city" placeholder="City" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Country <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.country" placeholder="Country" class="form-input" required autocomplete="off" />
        </label>
        
        
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Business License No.</span>
            <input type="text" v-model.trim="form.business_license_no" placeholder="Business License No." class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">BIN/GST/SST No.</span>
            <input type="text" v-model.trim="form.bin_gst_sst_no" placeholder="BIN/GST/SST No." class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm"></label>
        <label class="block w-full mt-2 text-sm"></label>

    </div>
    <div class="mt-3 md:mt-8">
      <h4 class="text-md font-semibold">Contact Info</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Phone <span class="text-red-500">*</span></span>
              <input type="text" v-model.trim="form.phone" placeholder="Phone" class="form-input" required autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Company Reg. No</span>
              <input type="text" v-model.trim="form.company_reg_no" placeholder="Company Reg. No" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Email <small>(General)</small></span>
              <input type="email" v-model.trim="form.email_general" placeholder="Email (General)" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Email <small>(Agreement)</small></span>
              <input type="text" v-model.trim="form.email_agreement" placeholder="Email (Agreement)" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Email <small>(Invoice)</small></span>
              <input type="text" v-model.trim="form.email_invoice" placeholder="Email (Invoice)" class="form-input" autocomplete="off" />
          </label>
      </div>
    </div>
    <ErrorComponent :errors="errors"></ErrorComponent>

</template>
<script setup>
import Error from "../../Error.vue";
import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
import ErrorComponent from '../../../components/utils/ErrorComponent.vue';

const props = defineProps({
    form: {
        required: false,
        default: {}
    },
    errors: { type: [Object, Array], required: false },
});
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