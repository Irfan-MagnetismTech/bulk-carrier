<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Certificate Authority <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.authority" placeholder="Certificate Authority" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.authority" :errors="errors.authority" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Certificate Name <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.name" placeholder="Certificate Name" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.name" :errors="errors.name" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Certificate Type <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.type" placeholder="Certificate Type" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.type" :errors="errors.type" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Validity Period <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.validity" placeholder="Validity Period" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.validity" :errors="errors.validity" />
        </label>
    </div>
</template>
<script setup>
import Error from "../Error.vue";
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
  @apply text-gray-700 ;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed ;
}
.form-input {
  @apply block mt-1 text-sm rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple ;
}
</style>