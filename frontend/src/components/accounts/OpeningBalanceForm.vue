<script setup>
import useTransaction from "../../composables/accounts/useTransaction.js";

const { bgColor, allAccount, getAccount } = useTransaction();
const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});

function fetchAccounts(search, loading) {
  if(search.length < 3) {
    return;
  } else {
    getAccount(search, loading);
  }
}

function setAccountId(event) {
  props.form.account_id = props.form.account.account_id;
  allAccount.value = [];
}

</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700">
        <div class="input-group">
          <label class="label-group">
            <span class="label-item-title"> Account <span class="required-style">*</span></span>
            <v-select :options="allAccount" placeholder="--Choose an option--" @search="fetchAccounts" v-model="form.account" label="account_name"  class="block w-full rounded form-input">
              <template #search="{attributes, events}">
                <input class="vs__search w-full" style="width: 50%" :required="!form.account_id" @change="setAccountId($event)" v-bind="attributes" v-on="events"/>
              </template>
            </v-select>
          </label>
            <label class="label-group">
                <span class="label-item-title"> Date <span class="required-style">*</span></span>
                <input type="date" class="label-item-input" v-model="form.date" required />
            </label>
        </div>
      <div class="input-group">
        <label class="label-group">
          <span class="label-item-title"> Debit Amount <span class="required-style">*</span></span>
          <input type="text" class="label-item-input" v-model="form.dr_amount" placeholder="Debit amount" />
        </label>
        <label class="label-group">
          <span class="label-item-title"> Credit Amount <span class="required-style">*</span></span>
          <input type="text" class="label-item-input" v-model="form.cr_amount" placeholder="Credit amount" />
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

>>> {
  --vs-controls-color: #374151;
  --vs-border-color: #4b5563;

  --vs-dropdown-bg: #282c34;
  --vs-dropdown-color: #eeeeee;
  --vs-dropdown-option-color: #eeeeee;

  --vs-selected-bg: #664cc3;
  --vs-selected-color: #374151;

  --vs-search-input-color: #4b5563;

  --vs-dropdown-option--active-bg: #664cc3;
  --vs-dropdown-option--active-color: #eeeeee;
}

</style>