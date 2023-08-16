<script setup>
const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  balanceIncomeLines : {
    
  },
  errors: { type: [Object, Array], required: false },
});
</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700">
        <div class="input-group">

            <label class="label-group">
                <span class="label-item-title"> Cash Account Name <span class="required-style">*</span></span>
                <input type="text" class="label-item-input" v-model="form.name" placeholder="Cash Account Name" required />
            </label>

        </div>
    </div>
</template>
<style lang="postcss" scoped>
.input-group {
    @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
}
.label-group {
    @apply block w-full mt-3 text-sm font-semibold;
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
.vs__selected{
  display: none !important;
}
.required-style{
    @apply text-red-400 font-semibold
}

</style>