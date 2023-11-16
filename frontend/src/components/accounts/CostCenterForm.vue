<script setup>
import Error from "../Error.vue";
import {onMounted, ref} from "vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import Store from "../../store";

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  page: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});

const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

onMounted(() => {
  //props.form.business_unit = businessUnit.value;
});

</script>
<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Cost Center Name <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.name" v-model="form.name" placeholder="Cost center name" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.name" :errors="errors.name" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Short Name <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.short_name" placeholder="Short name" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.short_name" :errors="errors.short_name" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Type <span class="text-red-500">*</span></span>
        <select class="form-input" v-model.trim="form.type" autocomplete="off" required>
          <option value="" disabled selected>Select</option>
          <option value="Lighter Vessel">Lighter Vessel</option>
          <option value="Bulk Carrier">Bulk Carrier</option>
          <option value="Head Office">Head Office</option>
        </select>
        <Error v-if="errors?.type" :errors="errors.type" />
      </label>
    </div>
</template>
<style lang="postcss" scoped></style>