<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useAppraisalForm from '../../../composables/crew/useAppraisalForm';

const icons = useHeroIcon();

const route = useRoute();
const appraisalFormId = route.params.appraisalFormId;
const { appraisalForm, showAppraisalForm, errors } = useAppraisalForm();

const { setTitle } = Title();

setTitle('Appraisal Form Details');

onMounted(() => {
  showAppraisalForm(appraisalFormId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Appraisal Form Details</h2>
    <default-button :title="'Appraisal Form List'" :to="{ name: 'crw.appraisal-forms.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <div class="flex md:gap-4 grid col-1">
        <div class="w-full">
          <table class="w-full">
            <thead>
            <tr>
              <td class="!text-center bg-green-600 text-white font-bold uppercase" colspan="2">Basic Info</td>
            </tr>
            </thead>
            <tbody>
              <tr>
                <th class="w-40">Business Unit</th>
                <td><span :class="appraisalForm?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ appraisalForm?.business_unit }}</span></td>
              </tr>
             <tr>
                <th class="w-40">Form No</th>
                <td>{{ appraisalForm?.form_no }}</td>
              </tr>
             <tr>
                <th class="w-40">Form Name</th>
                <td>{{ appraisalForm?.form_name }}</td>
              </tr>
             <tr>
                <th class="w-40">Version</th>
                <td>{{ appraisalForm?.version }}</td>
              </tr>
              
             <tr>
                <th class="w-40">Total Marks</th>
                <td>{{ appraisalForm?.total_marks }}</td>
              </tr>
              
             <tr>
                <th class="w-40">Description</th>
                <td>{{ appraisalForm?.description }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <table class=" w-full ">
          <thead>
          <tr class=" bg-green-600 text-white">
            <td colspan="5" class="!text-center bg-green-600 text-white font-bold uppercase">Form Preview</td>
          </tr>
          <tr class="bg-gray-300 text-black-700">
            <th class="text-center w-1/5">Section No</th>
            <th class="text-center w-1/5">Section Name</th>
            <th class="text-center w-1/5">Aspect</th>
            <th class="text-center w-1/5">Description</th>
            <th class="text-center w-1/5">Answer Type</th>
          </tr>
          </thead>
          <tbody class="">


          <template v-for="(appraisalFormLine, index) in appraisalForm?.appraisalFormLines" :key="index">

            <tr v-for="(appraisalFormLineItem, appraisalFormLineItemIndex) in appraisalFormLine?.appraisalFormLineItems" :key="appraisalFormLineItemIndex">
              <td v-if="appraisalFormLineItemIndex == 0" :rowspan="appraisalFormLine?.appraisalFormLineItems?.length ?? 1" class="!text-center">{{ appraisalFormLine?.section_no }}</td>
              <td v-if="appraisalFormLineItemIndex == 0" :rowspan="appraisalFormLine?.appraisalFormLineItems?.length ?? 1">{{ appraisalFormLine?.section_name }}</td>
              <td>{{ appraisalFormLineItem?.aspect }}</td>
              <td>{{ appraisalFormLineItem?.description }}</td>
              <td class="text-center">{{ appraisalFormLineItem?.answer_type }}</td>

            </tr>
            <tr v-if="appraisalFormLine?.appraisalFormLineItems?.length == 0">
              <td class="!text-center">{{ appraisalFormLine?.section_no }}</td>
              <td>{{ appraisalFormLine?.section_name }}</td>
              <td></td>
              <td></td>
              <td></td>
            </tr>

          </template>


          </tbody>
        </table>
      </div>
  </div>
</template>
<style lang="postcss" scoped>
  th, td, tr {
    @apply text-left border-gray-500
  }

  #profileDetailTable th{
    text-align: center;
  }
  #profileDetailTable thead tr{
    @apply bg-gray-200
  }

  th.text-center, td.text-center, tr.text-center {
    @apply text-center border-gray-500
  }

</style>