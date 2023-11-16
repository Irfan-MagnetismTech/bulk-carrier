<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Cargo Type <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.cargo_type" placeholder="Cargo Type" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.cargo_type" :errors="errors.cargo_type" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Description</span>
            <input type="text" v-model.trim="form.description" placeholder="Description" class="form-input" autocomplete="off" />
          <Error v-if="errors?.description" :errors="errors.description" />
        </label>
    </div>
</template>
<script setup>
import Error from "../../Error.vue";
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