<script setup>
import Error from "../Error.vue";
const props = defineProps({
    form: { type: Object, required: true },
    errors: { type: [Object, Array], required: false },
});
</script>

<template>
    <!-- Name, Call Sign & IMO number -->
    <div class="input-group">
      <label class="label-group">
            <span class="label-item-title">Category <span class="text-red-500">*</span></span>
        <select type="text" v-model.trim="form.category" required placeholder="Enter Category" class="label-item-input">
          <option value disabled selected>Select</option>
          <option value="freight" :selected="form.category === 'freight'">Freight</option>
          <option value="surcharge" :selected="form.category === 'surcharge'">Surcharge</option>
        </select>
        <Error v-if="errors?.category" :errors="errors.category" />
      </label>
      <label class="label-group">
            <span class="label-item-title">
                Head
                <span class="text-red-500">*</span>
            </span>
        <input type="text" v-model.trim="form.name" required placeholder="Enter Name" class="label-item-input" />
        <Error v-if="errors?.name" :errors="errors.name" />
      </label>
        <label class="label-group">
            <span class="label-item-title">
                Short Code
                <span class="text-red-500">*</span>
            </span>
            <input type="text" v-model.trim="form.code" required placeholder="Enter Code" class="label-item-input" />
          <Error v-if="errors?.code" :errors="errors.code" />
        </label>
        <label class="label-group">
              <span class="label-item-title">
                  Unit
                  <span class="text-red-500">*</span>
              </span>
          <select type="text" v-model.trim="form.unit" required placeholder="Enter Unit" class="label-item-input">
            <option value disabled selected>Select</option>
            <option value="tue" :selected="form.unit === 'tue'">Tue</option>
            <option value="container" :selected="form.unit === 'container'">Container</option>
            <option value="plug" :selected="form.unit === 'plug'">Plug</option>
            <option value="lump-sum" :selected="form.unit === 'lump-sum'">Lump-Sum</option>
          </select>
          <Error v-if="errors?.unit" :errors="errors.unit" />
        </label>
    </div>

    <!-- Remarks -->
    <label class="label-group">
        <span class="label-item-title">Remarks</span>
        <textarea v-model="form.remarks" placeholder="Enter Remarks" class="label-item-input"></textarea>
      <Error v-if="errors?.remarks" :errors="errors.remarks" />
    </label>
</template>

<style lang="postcss" scoped>
.input-group {
    @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
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