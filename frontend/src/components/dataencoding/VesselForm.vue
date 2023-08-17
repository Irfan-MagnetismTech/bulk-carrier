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
                Name
                <span class="text-red-500">*</span>
            </span>
            <input type="text" v-model.trim="form.name" required placeholder="Enter Vessel Name" class="label-item-input" />
          <Error v-if="errors?.name" :errors="errors.name" />
        </label>
        <label class="label-group">
              <span class="label-item-title">
                  Code
                  <span class="text-red-500">*</span>
              </span>
          <input type="text" v-model.trim="form.code" required placeholder="Enter Vessel Name" class="label-item-input" />
          <Error v-if="errors?.code" :errors="errors.code" />
        </label>
        <label class="label-group">
                <span class="label-item-title">
                    Ownership
                    <span class="text-red-500">*</span>
                </span>
          <select v-model="form.ownership" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <option value="">--Choose an Option--</option>
            <option value="Owner">Owner</option>
            <option value="Chartered">Chartered</option>
          </select>
          <Error v-if="errors?.ownership" :errors="errors.ownership" />
        </label>
        <label class="label-group">
            <span class="label-item-title">
                Call Sign
                <span class="text-red-500">*</span>
            </span>
            <input type="text" v-model.trim="form.call_sign" required placeholder="Enter Call Sign" class="label-item-input" />
          <Error v-if="errors?.call_sign" :errors="errors.call_sign" />
        </label>
    </div>
    <!-- Flag, Built Year & Speed -->
    <div class="input-group">
      <label class="label-group">
            <span class="label-item-title">
                IMO/Lloyd Number
                <span class="text-red-500">*</span>
            </span>
        <input type="number" v-model.number="form.imo_number" required placeholder="Enter IMO/Lloyd Number" class="label-item-input" />
        <Error v-if="errors?.imo_number" :errors="errors.imo_number" />
      </label>
        <label class="label-group">
            <span class="label-item-title">
                Flag
                <span class="text-red-500">*</span>
            </span>
            <input type="text" v-model.trim="form.flag" required placeholder="Enter Flag" class="label-item-input" />
          <Error v-if="errors?.flag" :errors="errors.flag" />
        </label>
        <label class="label-group">
            <span class="label-item-title">
                Year Built
                <span class="text-red-500">*</span>
            </span>
            <input type="number" v-model.number="form.year_built" required placeholder="Enter Built Year" class="label-item-input" />
          <Error v-if="errors?.year_built" :errors="errors.year_built" />
        </label>
        <label class="label-group">
            <span class="label-item-title">
                Speed (Knots)
                <span class="text-red-500">*</span>
            </span>
            <input type="text" v-model="form.speed" step="0.00001" required placeholder="Enter Speed (Knots)" class="label-item-input" />
          <Error v-if="errors?.speed" :errors="errors.speed" />
        </label>
    </div>
    <!-- Grt, nrt & dwt -->
    <div class="input-group">
        <label class="label-group">
            <span class="label-item-title">
                GRT
                <span class="text-red-500">*</span>
            </span>
            <input type="number" v-model.number="form.grt" required placeholder="Enter GRT" class="label-item-input" />
          <Error v-if="errors?.grt" :errors="errors.grt" />
        </label>
        <label class="label-group">
            <span class="label-item-title">
                NRT
                <span class="text-red-500">*</span>
            </span>
            <input type="number" v-model.number="form.nrt" required placeholder="Enter NRT" class="label-item-input" />
          <Error v-if="errors?.nrt" :errors="errors.nrt" />
        </label>
        <label class="label-group">
            <span class="label-item-title">
                DWT
                <span class="text-red-500">*</span>
            </span>
            <input type="text" v-model="form.dwt" required placeholder="Enter DWT" class="label-item-input" />
          <Error v-if="errors?.dwt" :errors="errors.dwt" />
        </label>
      <label class="label-group">
            <span class="label-item-title">
                Capacity TEUs
                <span class="text-red-500">*</span>
            </span>
        <input type="number" v-model.number="form.capacity_tues" required placeholder="Enter Capacity TEUs" class="label-item-input" />
        <Error v-if="errors?.capacity_tues" :errors="errors.capacity_tues" />
      </label>
    </div>
    <!-- Capacity tues, Reefer Capacity & Classification -->
    <div class="input-group">
        <label class="label-group">
            <span class="label-item-title">
                Reefer(420V)/110V
                <span class="text-red-500">*</span>
            </span>
            <input type="number" v-model="form.reefer_capacity" required placeholder="Enter Reefer(420V)/110V" min="0" step="any" class="label-item-input" />
          <Error v-if="errors?.reefer_capacity" :errors="errors.reefer_capacity" />
        </label>
        <label class="label-group">
            <span class="label-item-title">
                Classification
                <span class="text-red-500">*</span>
            </span>
            <input type="text" v-model.trim="form.classification" required placeholder="Enter Classification" class="label-item-input" />
          <Error v-if="errors?.classification" :errors="errors.classification" />
        </label>
      <label class="label-group">
              <span class="label-item-title">
                  Available Stowage
                  <!-- <span class="text-red-500">*</span> -->
              </span>
        <input type="number" v-model.number="form.available_stowages" placeholder="Available Storage" class="label-item-input" />
        <Error v-if="errors?.available_stowages" :errors="errors.available_stowages" />
      </label>
      <label class="label-group">
              <span class="label-item-title">
                  Tier Range
                  <!-- <span class="text-red-500">*</span> -->
              </span>
        <input type="number" v-model="form.tier_range" placeholder="Enter Tier Range" min="0" step="any" class="label-item-input" />
        <Error v-if="errors?.tier_range" :errors="errors.tier_range" />
      </label>
    </div>
    <div class="input-group">
      <label class="label-group">
        <span class="label-item-title">Deck Range</span>
        <textarea v-model="form.deck_range" placeholder="Enter deck range" class="label-item-input"></textarea>
        <Error v-if="errors?.deck_range" :errors="errors.deck_range" />
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