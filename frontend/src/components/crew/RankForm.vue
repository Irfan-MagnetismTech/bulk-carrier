<script setup>
import Error from "../Error.vue";
import Editor from '@tinymce/tinymce-vue';

import useRole from "../../composables/administration/useRole";
import {onMounted, ref} from "vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import Store from "../../store";
import ErrorComponent from '../../components/utils/ErrorComponent.vue';

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

const { roles, getRoles } = useRole();
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

onMounted(() => {

});

</script>
<template>
    <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Rank Name <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.name" placeholder="Rank Name" class="form-input" autocomplete="off" required />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Short Name <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.short_name" placeholder="Short Name" class="form-input" autocomplete="off" required />
      </label>
    </div>
  <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<style lang="postcss" scoped>
</style>