<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Certificate Authority <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.authority" placeholder="Certificate Authority" class="form-input" required autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Certificate Name <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.name" placeholder="Certificate Name" class="form-input" required autocomplete="off" :class="{ 'bg-gray-100': formType === 'edit' }" :disabled="formType=='edit'" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Certificate Type <span class="text-red-500">*</span></span>
            <select v-model.trim="form.type" class="form-input" required>
                <option value="" disabled selected>Select</option>
                <option value="Renewable">Renewable</option>
                <option value="Permanent">Permanent</option>
              </select>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Validity Period <span class="text-red-500">*</span></span>
            <select v-model.trim="form.validity" class="form-input" required>
                <option  v-if="form.type!='Permanent'" value="" disabled>Select</option>
                <option  v-if="form.type!='Permanent'" value="3">3 Months</option>
                <option  v-if="form.type!='Permanent'" value="6">6 Months</option>
                <option  v-if="form.type!='Permanent'" value="12">1 Year</option>
                <option  v-if="form.type!='Permanent'" value="24">2 Years</option>
                <option  v-if="form.type!='Permanent'" value="36">3 Years</option>
                <option  v-if="form.type!='Permanent'" value="48">4 Years</option>
                <option  v-if="form.type!='Permanent'" value="60">5 Years</option>
                <option  v-if="form.type!='Permanent'" value="120">10 Years</option>
                <option value="0" v-if="form.type=='Permanent'">Permanent</option>
              </select>
        </label>
    </div>

    <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<script setup>
import { ref, watch } from "vue";
import Error from "../Error.vue";
import ErrorComponent from '../../components/utils/ErrorComponent.vue';
const props = defineProps({
    form: {
        required: false,
        default: {}
    },
    errors: { type: [Object, Array], required: false },
    formType: { type: String, required : false },

});

watch(() => props.form.type, (value) => {
  if(value) {
    if(value == 'Permanent') {
      props.form.validity = '0'
    } else {
      props.form.validity = ''
    }
  }
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