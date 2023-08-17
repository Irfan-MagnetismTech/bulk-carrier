<script setup>
import {onMounted} from "@vue/runtime-core";
import Error from "../Error.vue";
const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});
</script>
<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">ISO Code <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.iso_code" placeholder="Iso Code" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          <Error v-if="errors?.iso_code" :errors="errors.iso_code" />
        </label>
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Tues <span class="text-red-500">*</span></span>
          <input type="number" step=".01" v-model="form.tues" required placeholder="Tues" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          <Error v-if="errors?.tues" :errors="errors.tues" />
        </label>
        <!-- <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Dimension</span>
          <input type="text" v-model="form.dimension" placeholder="Dimension" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          <Error v-if="errors?.dimension" :errors="errors.dimension" />
        </label> -->
    </div>
    <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Group <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.group" required placeholder="Group" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        <Error v-if="errors?.group" :errors="errors.group" />
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Description</span>
        <input type="text" v-model="form.description" placeholder="Description" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        <Error v-if="errors?.description" :errors="errors.description" />
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Arch Type</span>
        <input type="text" v-model="form.arche_type" placeholder="Arch Type" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        <Error v-if="errors?.arche_type" :errors="errors.arche_type" />
      </label>
    </div>
    <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Arch Iso</span>
        <input type="text" v-model="form.arch_iso" placeholder="Arch ISO" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        <Error v-if="errors?.arch_iso" :errors="errors.arch_iso" />
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Tare Weight <span class="text-red-500">*</span></span>
        <input type="number" step=".01" required v-model="form.tare_weight" placeholder="Tare weight" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        <Error v-if="errors?.tare_weight" :errors="errors.tare_weight" />
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Safe Weight <span class="text-red-500">*</span></span>
        <input type="text" required v-model="form.safe_weight" placeholder="Safe weight" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        <Error v-if="errors?.safe_weight" :errors="errors.safe_weight" />
      </label>
    </div>
    <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Class</span>
        <input type="text" v-model="form.class" placeholder="Class" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        <Error v-if="errors?.class" :errors="errors.class" />
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Refer Type</span>
        <input type="text" v-model="form.refer_type" placeholder="Refer type" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        <Error v-if="errors?.refer_type" :errors="errors.refer_type" />
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Container Length</span>
        <input type="text" v-model="form.container_length" placeholder="Container length" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        <Error v-if="errors?.container_length" :errors="errors.container_length" />
      </label>
    </div>
  <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Container Height</span>
      <input type="text" v-model="form.container_height" placeholder="Container height" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      <Error v-if="errors?.container_height" :errors="errors.container_height" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Length MM</span>
      <input type="text" v-model="form.length_mm" placeholder="Length MM" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      <Error v-if="errors?.length_mm" :errors="errors.length_mm" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Height MM</span>
      <input type="text" v-model="form.height_mm" placeholder="Height MM" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      <Error v-if="errors?.height_mm" :errors="errors.height_mm" />
    </label>
  </div>
  <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Width MM</span>
      <input type="text" v-model="form.width_mm" placeholder="Width mm" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      <Error v-if="errors?.width_mm" :errors="errors.width_mm" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300"></span>
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300"></span>
    </label>
  </div>
</template>
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