<script setup>
import Error from "../Error.vue";
import usePort from '../../composables/usePort';
import { ref, watch, onMounted } from 'vue';

const { portName, getPortsByNameOrCode } = usePort();

const props = defineProps({
    form: { type: Object, required: true },
    errors: { type: [Object, Array], required: false },
});

function checkReadonly()
{
  if(props.form.is_readonly == 1) {
    return "bg-gray-300";
  } else {
    return true;
  }
}

function fetchOptions(search, loading) { 
    getPortsByNameOrCode(search, props.form.service_id);
}

watch(() => props.form.port_origin_name, (value) => {
  if (value) {
    props.form.port = value.code;
  }
}, {deep: true});

</script>

<template>
    <!-- Name, Call Sign & IMO number -->
    <div class="input-group">
        <label class="label-group">
            <span class="label-item-title">
                Partner Name
                <span class="text-red-500">*</span>
            </span>
            <input type="text" v-model="form.name" required placeholder="Enter Partner Name" class="label-item-input" />
          <Error v-if="errors?.name" :errors="errors.name" />
        </label>
        <label class="label-group">
            <span class="label-item-title">Port <span class="text-red-500">*</span></span>
            <v-select v-model="form.port_origin_name" :id="'port' + index" @search="fetchOptions" value="id" :options="portName" label="code_name" placeholder="Enter Port Code or Name" class="mt-1 placeholder-gray-600 w-full"></v-select>
            <input type="hidden" v-model="form.port" name="port_code" :id="'port_code' + index" />
          </label>
        <label class="label-group">
            <span class="label-item-title">
                Agent Code
                <span class="text-red-500">*</span>
            </span>
            <input type="text" v-model="form.code" required placeholder="Enter Agent Code" class="label-item-input" :readonly="form.is_readonly == 1" :class="checkReadonly()"/>
          <Error v-if="errors?.code" :errors="errors.code" />
        </label>
    </div>
    <div class="input-group">
        <label class="label-group">
            <span class="label-item-title">
                MLO Codes
                <span class="text-red-500">*</span>
            </span>
            <textarea type="text" v-model="form.mlos" required placeholder="Enter MLO Codes" class="label-item-input"></textarea>
          <Error v-if="errors?.mlos" :errors="errors.mlos" />
        </label>
    </div>
    <div class="input-group">
      <label class="label-group">
              <span class="label-item-title">
                  Address
                <span class="text-red-500">*</span>
              </span>
        <input type="text" v-model="form.address" placeholder="Agent address" class="label-item-input" required/>
        <Error v-if="errors?.address" :errors="errors.address" />
      </label>
    </div>
  <div class="input-group">
    <label class="label-group">
            <span class="label-item-title">
                Export Advisory Email
                <span class="text-red-500">*</span>
            </span>
      <textarea v-model="form.export_email" required placeholder="Export Advisory email" class="label-item-input"></textarea>
      <Error v-if="errors?.export_email" :errors="errors.export_email" />
    </label>
    <label class="label-group">
            <span class="label-item-title">
                Export CC Email
            </span>
      <textarea v-model="form.export_cc_email" placeholder="Export CC Email" class="label-item-input"></textarea>
      <Error v-if="errors?.export_cc_email" :errors="errors.export_cc_email" />
    </label>
  </div>
  <div class="input-group">
    <label class="label-group">
            <span class="label-item-title">
                Import Advisory Email
                <span class="text-red-500">*</span>
            </span>
      <textarea v-model="form.import_email" required placeholder="Import Advisory email" class="label-item-input"></textarea>
      <Error v-if="errors?.import_email" :errors="errors.import_email" />
    </label>
    <label class="label-group">
            <span class="label-item-title">
                Import CC Email
            </span>
      <textarea v-model="form.import_cc_email" placeholder="Import CC Email" class="label-item-input"></textarea>
      <Error v-if="errors?.import_cc_email" :errors="errors.import_cc_email" />
    </label>
  </div>
  <div class="input-group">
    <label class="label-group">
            <span class="label-item-title">
                HRLines CC Email
            </span>
      <textarea v-model="form.hrlines_cc_email" placeholder="Import Advisory email" class="label-item-input"></textarea>
      <Error v-if="errors?.hrlines_cc_email" :errors="errors.hrlines_cc_email" />
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