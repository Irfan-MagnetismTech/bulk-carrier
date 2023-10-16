<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Certificate Authority <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.authority" placeholder="Certificate Authority" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.authority" :errors="errors.authority" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Certificate Name <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.name" placeholder="Certificate Name" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.name" :errors="errors.name" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Certificate Type <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.type" placeholder="Certificate Type" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.type" :errors="errors.type" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Validity Period <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.validity" placeholder="Validity Period" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.validity" :errors="errors.validity" />
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