<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Update Delivery Redelivery</h2>
    <default-button :title="'Delivery Redelivery List'" :to="{ name: 'ops.delivery-redelivery.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
      <form @submit.prevent="updateDeliveryRedelivery(deliveryRedelivery, deliveryRedeliveryId)">
          <!-- Port Form -->
          <delivery-redelivery-form v-model:form="deliveryRedelivery" :errors="errors" :formType="formType" :deliveryRedeliveryLineObject="deliveryRedeliveryLineObject"></delivery-redelivery-form>
          <!-- Submit button -->
          <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
      </form>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import DeliveryRedeliveryForm from '../../../components/operations/DeliveryRedeliveryForm.vue';
import useDeliveryRedelivery from '../../../composables/operations/useDeliveryRedelivery';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const deliveryRedeliveryId = route.params.deliveryRedeliveryId;
const { deliveryRedelivery, deliveryRedeliveryLineObject, showDeliveryRedelivery, updateDeliveryRedelivery, errors } = useDeliveryRedelivery();

const { setTitle } = Title();

setTitle('Edit Delivery Redelivery');

const formType = 'edit';

onMounted(() => {
  showDeliveryRedelivery(deliveryRedeliveryId);
});
</script>