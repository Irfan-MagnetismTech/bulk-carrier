<script setup>
const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});

</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700">
        <div class="input-group">
            <label class="label-group">
                <span class="label-item-title">Fuel Name <span class="required-style">*</span></span>
                <input type="text" class="label-item-input" v-model="form.fuel_name" placeholder="Fuel name" required />
            </label>
            <label class="label-group">
              <span class="label-item-title">Current Price <span class="required-style">*</span></span>
              <input type="number" step=".01" class="label-item-input" v-model="form.current_price" placeholder="Current price" required />
            </label>
            <label class="label-group">
              <span class="label-item-title">Average Purchase Price <span class="required-style">*</span></span>
              <input type="number" step=".01" class="label-item-input" v-model="form.average_purchase" placeholder="Average price" required />
            </label>
            <label class="label-group">
              <span class="label-item-title">Purchase Price <span class="required-style">*</span></span>
              <input type="number" step=".01" class="label-item-input" v-model="form.purchase_price" placeholder="Purchase price" required />
            </label>
        </div>
        <div class="input-group">
          <label class="label-group">
            <span class="label-item-title">Status <span class="required-style">*</span></span>
            <select class="label-item-input" v-model="form.status" required>
              <option value="" selected disabled>Select option</option>
              <option value="1">Available</option>
              <option value="0">Unavailable</option>
            </select>
          </label>
          <label class="label-group">
            <span class="label-item-title">Is Cng <span class="required-style">*</span></span>
            <select class="label-item-input" v-model="form.is_cng" required>
              <option value="" selected disabled>Select option</option>
              <option value="1">Yes</option>
              <option value="0">No</option>
            </select>
          </label>
          <label class="label-group"></label>
          <label class="label-group"></label>
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