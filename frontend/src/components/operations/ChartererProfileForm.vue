<template>

    <h4 class="text-md font-semibold">Basic Info</h4>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>

        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Charterer Name <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.name" placeholder="Charterer Name" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.name" :errors="errors.name" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Company Legal Name <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.company_legal_name" placeholder="Company Legal Name" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.company_legal_name" :errors="errors.company_legal_name" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Charterer Owner Code <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.owner_code" placeholder="Charterer Owner Code" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.owner_code" :errors="errors.owner_code" />
        </label>
        
        
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Contact No</span>
            <input type="text" v-model.trim="form.contact_no" placeholder="Contact No" class="form-input" autocomplete="off" />
          <Error v-if="errors?.contact_no" :errors="errors.contact_no" />
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Country</span>
            <input type="text" v-model.trim="form.country" placeholder="Country" class="form-input" autocomplete="off" />
          <Error v-if="errors?.country" :errors="errors.country" />
        </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Address</span>
          <input type="text" v-model.trim="form.address" placeholder="Address" class="form-input" autocomplete="off" />
        <Error v-if="errors?.address" :errors="errors.address" />
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Billing Address</span>
            <input type="text" v-model.trim="form.billing_address" placeholder="Billing Address" class="form-input" autocomplete="off" />
          <Error v-if="errors?.billing_address" :errors="errors.billing_address" />
        </label>
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">

      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Billing Email </span>
            <input type="text" v-model.trim="form.billing_email" placeholder="Billing Email" class="form-input" autocomplete="off" />
          <Error v-if="errors?.billing_email" :errors="errors.billing_email" />
        </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Email</span>
            <input type="text" v-model.trim="form.email" placeholder="Email" class="form-input" autocomplete="off" />
          <Error v-if="errors?.email" :errors="errors.email" />
      </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Website</span>
          <input type="text" v-model.trim="form.website" placeholder="Website" class="form-input" autocomplete="off" />
        <Error v-if="errors?.website" :errors="errors.website" />
      </label>
      <label class="block w-full mt-2 text-sm"></label>
    </div>
    
    <h4 class="text-md font-semibold mt-4">Bank Account Info</h4>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Bank Name</span>
            <input type="text" v-model.trim="form.opsChartererBankAccounts[0].bank_name" placeholder="Bank Name" class="form-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Bank Branch </span>
            <input type="text" v-model.trim="form.opsChartererBankAccounts[0].bank_branch_name" placeholder="Bank Branch" class="form-input" autocomplete="off" />
        </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Account No</span>
          <input type="text" v-model.trim="form.opsChartererBankAccounts[0].account_no" placeholder="Account No" class="form-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Account Name</span>
            <input type="text" v-model.trim="form.opsChartererBankAccounts[0].account_name" placeholder="Account Name" class="form-input" autocomplete="off" />
        </label>
        
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Swift Code</span>
            <input type="text" v-model.trim="form.opsChartererBankAccounts[0].swift_code" placeholder="Swift Code" class="form-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Routing No</span>
          <input type="text" v-model.trim="form.opsChartererBankAccounts[0].routing_no" placeholder="Routing No" class="form-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Currency</span>
              <select v-model="form.opsChartererBankAccounts[0].currency" class="form-input">
                <option value="">Select Currency</option>
                <option v-for="currency in currencies">{{ currency }}</option>
              </select>
              <Error v-if="errors?.model_name" :errors="errors.model_name" />
      </label>
      <label class="block w-full mt-2 text-sm"></label>
    </div>

</template>
<script setup>
import { ref, watch, onMounted } from "vue";
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import {useStore} from "vuex";
import useBusinessInfo from "../../composables/useBusinessInfo"

const store = useStore();
const editInitiated = ref(false);
const { getCurrencies, currencies } = useBusinessInfo();

const props = defineProps({
    form: {
        required: false,
        default: {},
    },
    errors: { type: [Object, Array], required: false },
    formType: { type: String, required : false }
});


watch(() => props.form, (value) => {

  if(props?.formType == 'edit' && editInitiated.value != true) {

  //   vessels.value = [props?.form?.opsVessel]

  //   if(vessels.value.length > 0) {
  //       console.log("Changing editInitatedValue ")
  //       editInitiated.value = true
  //     }
  }
}, {deep: true});


onMounted(() => {
  getCurrencies();
})

</script>
<style lang="postcss" scoped>
.input-group {
  @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
}
.label-group {
  @apply block w-full mt-3 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark-disabled:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray;
}
</style>