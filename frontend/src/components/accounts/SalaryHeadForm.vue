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

});

</script>
<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Cost Center Name <span class="text-red-500">*</span></span>
      <input type="text" v-model.trim="form.name" v-model="form.name" placeholder="Cost center name" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.name" :errors="errors.name" />
    </label>
  </div>
</template>
<style lang="postcss" scoped></style>