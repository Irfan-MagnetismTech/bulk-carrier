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
            <span class="label-item-title">
                Code
                <span class="text-red-500">*</span>
            </span>
            <input type="text" autocomplete="nope" v-model="form.code" required placeholder="Enter Slot Partner Code" class="label-item-input" />
            <Error v-if="errors?.code" :errors="errors.code" />
        </label>
        <label class="label-group">
            <span class="label-item-title">
                Name
                <span class="text-red-500">*</span>
            </span>
            <input type="text" autocomplete="nope" v-model="form.name" required placeholder="Enter Slot Partner Name" class="label-item-input" />
            <Error v-if="errors?.name" :errors="errors.name" />
        </label>
      <label class="label-group">
            <span class="label-item-title">
                Country
            </span>
        <input type="text" autocomplete="nope" v-model="form.country" placeholder="Enter Slot Partner Country" class="label-item-input" />
        <Error v-if="errors?.country" :errors="errors.country" />
      </label>
      <label class="label-group">
            <span class="label-item-title">
                Type
            </span>
        <select v-model="form.type" class="label-item-input" >
          <option value="" selected disabled>Select</option>
          <option value="MLO">MLO</option>
          <option value="NVOCC">NVOCC</option>
          <option value="FEEDER">FEEDER</option>
          <option value="FRD">FREIGHT FORWARDER</option>
          <option value="OTHERS">OTHERS</option>
        </select>
        <Error v-if="errors?.type" :errors="errors.type" />
      </label>
    </div>
  <div class="input-group">
    <label class="label-group">
            <span class="label-item-title">
                Principal Office Address
            </span>
      <textarea type="text" autocomplete="nope" v-model="form.principal_office_address" placeholder="Enter Slot Partner Principal office address" class="label-item-input" ></textarea>
      <Error v-if="errors?.principal_office_address" :errors="errors.principal_office_address" />
    </label>
    <label class="label-group">
            <span class="label-item-title">
            </span>
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