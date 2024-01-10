<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-1/2 text-sm" v-if="businessUnit === 'ALL'">
          <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Port/Ghat Code <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.code" maxlength="10" placeholder="Port/Ghat Code" class="form-input" required autocomplete="off" :class="{ 'bg-gray-100': formType === 'edit' }" :disabled="formType=='edit'"/>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Port/Ghat Name <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.name" placeholder="Port/Ghat Name" class="form-input" required autocomplete="off" />
        </label>
    </div>

    <ErrorComponent :errors="errors"></ErrorComponent>
    
</template>
<script setup>
import Error from "../../Error.vue";
import { ref } from "vue";
import ErrorComponent from '../../../components/utils/ErrorComponent.vue';
import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

const props = defineProps({
    form: {
        required: false,
        default: {}
    },
    errors: { type: [Object, Array], required: false },
    formType: { type: String, required : false },
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
  @apply text-gray-700 dark-disabled:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray;
}
</style>